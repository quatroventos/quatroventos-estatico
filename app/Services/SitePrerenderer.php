<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Gera o HTML público com scripts/prerender.mjs. Cada execução usa o próprio arquivo de payloads,
 * para que a geração completa (fila) e a geração sob demanda (primeira visita) não se atropelem.
 */
class SitePrerenderer
{
    /**
     * @param  list<array{page: array<string, mixed>, site: mixed}>  $payloads
     * @param  (callable(string, string): void)|null  $output
     */
    public function render(array $payloads, int $timeout = 600, ?callable $output = null): void
    {
        $file = storage_path('app/prerender-payloads-'.Str::lower((string) Str::ulid()).'.json');
        File::ensureDirectoryExists(dirname($file));
        File::put($file, json_encode($payloads, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        try {
            $process = new Process(['node', base_path('scripts/prerender.mjs'), $file], base_path(), null, null, $timeout);
            $process->run($output);

            if (! $process->isSuccessful()) {
                throw new RuntimeException(trim($process->getErrorOutput()) ?: 'A geração do HTML falhou.');
            }
        } finally {
            File::delete($file);
        }
    }

    /**
     * Caminho do HTML gerado para um payload de página; segue a mesma regra de scripts/prerender.mjs.
     *
     * @param  array<string, mixed>  $page
     */
    public static function fileFor(array $page): string
    {
        $locale = is_string($page['locale'] ?? null) && $page['locale'] !== '' ? $page['locale'] : 'pt-BR';
        $path = trim(is_string($page['path'] ?? null) ? $page['path'] : '/', '/');

        return storage_path('app/prerender/'.$locale.'/'.($path === '' ? 'index.html' : $path.'.html'));
    }
}
