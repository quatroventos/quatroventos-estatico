<?php

namespace App\Services\Cases;

use App\Models\CaseStudy;
use App\Models\MediaOptimization;
use App\Services\ImageOptimizationService;
use App\Support\Locales;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

/**
 * Leva cases de um ambiente para outro num .zip: manifest.json com os cases (e traduções), as linhas de
 * otimização das imagens e os arquivos do disco público que eles usam. Caminhos fora do storage (ex.: assets/
 * versionados no repositório) ficam como estão.
 */
class CaseTransfer
{
    public const FORMAT = 'quatroventos-cases';

    public const VERSION = 1;

    private const SKIP_ATTRIBUTES = ['id', 'translation_of', 'created_at', 'updated_at', 'deleted_at'];

    private const STORAGE_PATH = '#^/storage/([^?\#]+)$#';

    /**
     * @param  list<string>  $slugs  vazio exporta todos
     * @param  list<string>  $except
     * @return array{path: string, cases: list<string>, files: int, missing: list<string>}
     */
    public function export(string $destination, array $slugs = [], array $except = []): array
    {
        $cases = CaseStudy::query()
            ->whereNull('translation_of')
            ->where('locale', Locales::default())
            ->when($slugs !== [], fn ($query) => $query->whereIn('slug', $slugs))
            ->when($except !== [], fn ($query) => $query->whereNotIn('slug', $except))
            ->with('translations')
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        if ($cases->isEmpty()) {
            throw new RuntimeException('Nenhum case encontrado para exportar.');
        }

        $entries = [];
        $files = [];

        foreach ($cases as $case) {
            $attributes = $this->exportable($case);
            $translations = $case->translations->map(fn (CaseStudy $translation): array => $this->exportable($translation))->values()->all();
            $entries[] = ['attributes' => $attributes, 'translations' => $translations];

            foreach ([$attributes, ...$translations] as $row) {
                foreach ($this->storagePaths($row) as $path) {
                    $files[$path] = true;
                }
            }
        }

        $optimizations = MediaOptimization::query()
            ->whereIn('source_path', array_keys($files))
            ->where('status', MediaOptimization::STATUS_OPTIMIZED)
            ->whereNotNull('optimized_path')
            ->get();

        foreach ($optimizations as $optimization) {
            $files[$optimization->optimized_path] = true;
        }

        $disk = Storage::disk('public');
        $present = array_values(array_filter(array_keys($files), fn (string $path): bool => $disk->exists($path)));
        $missing = array_values(array_diff(array_keys($files), $present));

        File::ensureDirectoryExists(dirname($destination));
        $zip = new ZipArchive;

        if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Não consegui criar o arquivo '.$destination.'.');
        }

        $zip->addFromString('manifest.json', (string) json_encode([
            'format' => self::FORMAT,
            'version' => self::VERSION,
            'exported_at' => now()->toIso8601String(),
            'source' => config('app.url'),
            'cases' => $entries,
            'optimizations' => $optimizations->map(fn (MediaOptimization $row): array => collect($row->attributesToArray())->except(['id', 'created_at', 'updated_at'])->all())->values()->all(),
            'files' => $present,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        foreach ($present as $path) {
            $zip->addFile($disk->path($path), 'files/'.$path);
        }

        $zip->close();

        return [
            'path' => $destination,
            'cases' => $cases->pluck('slug')->all(),
            'files' => count($present),
            'missing' => $missing,
        ];
    }

    /**
     * @return array{
     *     cases: list<array{slug: string, action: string, status: string, translations: list<string>}>,
     *     files: array{written: int, skipped: int, renamed: list<string>},
     *     dry_run: bool
     * }
     */
    public function import(string $archive, bool $dryRun = false, bool $publish = false): array
    {
        $zip = new ZipArchive;

        if (! is_file($archive) || $zip->open($archive) !== true) {
            throw new RuntimeException('Não consegui abrir o arquivo '.$archive.'.');
        }

        try {
            $manifest = json_decode((string) $zip->getFromName('manifest.json'), true);

            if (! is_array($manifest) || ($manifest['format'] ?? null) !== self::FORMAT) {
                throw new RuntimeException('O arquivo não é uma exportação de cases.');
            }

            if ((int) ($manifest['version'] ?? 0) > self::VERSION) {
                throw new RuntimeException('A exportação é de uma versão mais nova da ferramenta.');
            }

            [$renames, $fileReport] = $this->planFiles($zip, (array) ($manifest['files'] ?? []), $dryRun);
            $report = [];

            $apply = function () use ($manifest, $renames, $publish, $dryRun, &$report): void {
                foreach ((array) ($manifest['cases'] ?? []) as $entry) {
                    $report[] = $this->importCase((array) $entry, $renames, $publish, $dryRun);
                }

                if (! $dryRun) {
                    $this->importOptimizations((array) ($manifest['optimizations'] ?? []), $renames);
                }
            };

            if ($dryRun) {
                $apply();
            } else {
                DB::transaction($apply);
                Cache::forget(ImageOptimizationService::MAP_KEY);
            }

            return ['cases' => $report, 'files' => $fileReport, 'dry_run' => $dryRun];
        } finally {
            $zip->close();
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function exportable(CaseStudy $case): array
    {
        return collect($case->attributesToArray())->except(self::SKIP_ATTRIBUTES)->all();
    }

    /**
     * Caminhos relativos ao disco público citados em qualquer valor do case (/storage/... ou media/..., optimized/...).
     *
     * @param  array<string, mixed>  $row
     * @return list<string>
     */
    private function storagePaths(array $row): array
    {
        $paths = [];

        array_walk_recursive($row, function (mixed $value) use (&$paths): void {
            if (is_string($value) && ($path = $this->relativeStoragePath($value)) !== null) {
                $paths[$path] = true;
            }
        });

        return array_keys($paths);
    }

    private function relativeStoragePath(string $value): ?string
    {
        $value = trim($value);

        if (preg_match(self::STORAGE_PATH, $value, $match) === 1) {
            $path = rawurldecode($match[1]);
        } elseif (preg_match('#^(media|optimized)/[^?\#]+$#', $value) === 1) {
            $path = $value;
        } else {
            return null;
        }

        return str_contains($path, '..') ? null : $path;
    }

    /**
     * Copia os arquivos para o disco público. Arquivo igual já existente é mantido; um diferente com o mesmo nome
     * faz o importado ganhar outro nome, e as referências são reescritas.
     *
     * @param  list<string>  $files
     * @return array{0: array<string, string>, 1: array{written: int, skipped: int, renamed: list<string>}}
     */
    private function planFiles(ZipArchive $zip, array $files, bool $dryRun): array
    {
        $disk = Storage::disk('public');
        $renames = [];
        $written = 0;
        $skipped = 0;

        foreach ($files as $path) {
            if (! is_string($path) || $this->relativeStoragePath($path) !== $path) {
                continue;
            }

            $contents = $zip->getFromName('files/'.$path);

            if ($contents === false) {
                continue;
            }

            $target = $path;

            if ($disk->exists($path)) {
                if (hash('sha256', (string) $disk->get($path)) === hash('sha256', $contents)) {
                    $skipped++;

                    continue;
                }

                $target = $this->freeName($path);
                $renames[$path] = $target;
            }

            if (! $dryRun) {
                $disk->put($target, $contents);
            }
            $written++;
        }

        return [$renames, ['written' => $written, 'skipped' => $skipped, 'renamed' => array_values($renames)]];
    }

    private function freeName(string $path): string
    {
        $disk = Storage::disk('public');
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $name = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        do {
            $candidate = ($directory === '.' ? '' : $directory.'/').$name.'-'.Str::lower(Str::random(6)).($extension !== '' ? '.'.$extension : '');
        } while ($disk->exists($candidate));

        return $candidate;
    }

    /**
     * @param  array<string, mixed>  $entry
     * @param  array<string, string>  $renames
     * @return array{slug: string, action: string, status: string, translations: list<string>}
     */
    private function importCase(array $entry, array $renames, bool $publish, bool $dryRun): array
    {
        $attributes = $this->rewrite((array) ($entry['attributes'] ?? []), $renames);
        $slug = (string) ($attributes['slug'] ?? '');

        if ($slug === '') {
            throw new RuntimeException('Case sem slug na exportação.');
        }

        $existing = CaseStudy::withTrashed()
            ->whereNull('translation_of')
            ->where('locale', Locales::default())
            ->where('slug', $slug)
            ->first();

        $status = $this->statusFor($existing, $publish);
        $action = $existing === null ? 'criar' : ($existing->trashed() ? 'restaurar' : 'atualizar');
        $translations = [];

        if (! $dryRun) {
            $case = $existing ?? new CaseStudy;
            $case->fill($this->withStatus($attributes, $status, $existing));
            $case->locale = Locales::default();
            $case->translation_of = null;
            $case->deleted_at = null;
            $case->save();
        }

        foreach ((array) ($entry['translations'] ?? []) as $translation) {
            $row = $this->rewrite((array) $translation, $renames);
            $locale = (string) ($row['locale'] ?? '');

            if ($locale === '' || Locales::isDefault($locale)) {
                continue;
            }

            $translations[] = $locale;

            if ($dryRun) {
                continue;
            }

            $current = CaseStudy::withTrashed()->where('translation_of', $case->id)->where('locale', $locale)->first();
            $model = $current ?? new CaseStudy;
            $model->fill($this->withStatus($row, $this->statusFor($current, $publish), $current));
            $model->translation_of = $case->id;
            $model->deleted_at = null;
            $model->save();
        }

        return ['slug' => $slug, 'action' => $action, 'status' => $status, 'translations' => $translations];
    }

    /**
     * Novo entra como rascunho; existente mantém o status atual. --publish publica todos.
     */
    private function statusFor(?CaseStudy $existing, bool $publish): string
    {
        if ($publish) {
            return 'published';
        }

        return $existing !== null && ! $existing->trashed() ? (string) $existing->status : 'draft';
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function withStatus(array $attributes, string $status, ?CaseStudy $existing): array
    {
        $attributes['status'] = $status;

        if ($status === 'published') {
            $attributes['published_at'] = $attributes['published_at'] ?? $existing?->published_at ?? now();
        }

        return collect($attributes)->except(self::SKIP_ATTRIBUTES)->all();
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @param  array<string, string>  $renames
     */
    private function importOptimizations(array $rows, array $renames): void
    {
        foreach ($rows as $row) {
            $source = (string) ($row['source_path'] ?? '');
            $optimized = (string) ($row['optimized_path'] ?? '');

            if ($source === '' || $optimized === '') {
                continue;
            }

            $source = $renames[$source] ?? $source;
            $optimized = $renames[$optimized] ?? $optimized;

            MediaOptimization::query()->updateOrCreate(
                ['source_path' => $source],
                [...collect($row)->except(['source_path', 'optimized_path', 'optimized_at'])->all(), 'optimized_path' => $optimized, 'optimized_at' => now()],
            );
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  array<string, string>  $renames
     * @return array<string, mixed>
     */
    private function rewrite(array $row, array $renames): array
    {
        if ($renames === []) {
            return $row;
        }

        array_walk_recursive($row, function (mixed &$value) use ($renames): void {
            if (! is_string($value) || ($path = $this->relativeStoragePath($value)) === null || ! isset($renames[$path])) {
                return;
            }

            $value = str_starts_with(trim($value), '/storage/') ? '/storage/'.$renames[$path] : $renames[$path];
        });

        return $row;
    }
}
