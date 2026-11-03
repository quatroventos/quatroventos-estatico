<?php

namespace App\Console\Commands;

use App\Services\Cases\CaseTransfer;
use Illuminate\Console\Command;
use RuntimeException;

/**
 * Exporta cases (com traduções, otimizações e mídias do storage) num .zip para importar em outro ambiente.
 */
class ExportCases extends Command
{
    protected $signature = 'cases:export
        {--slug= : Só estes cases, slugs separados por vírgula}
        {--except=exemplo-componentes : Slugs a deixar de fora, separados por vírgula}
        {--output= : Caminho do .zip (padrão: storage/app/exports/cases-AAAAMMDD-HHMMSS.zip)}';

    protected $description = 'Exporta cases e as mídias que eles usam num .zip.';

    public function handle(CaseTransfer $transfer): int
    {
        $output = (string) ($this->option('output') ?: storage_path('app/exports/cases-'.now()->format('Ymd-His').'.zip'));

        try {
            $result = $transfer->export($output, $this->list('slug'), $this->list('except'));
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info(count($result['cases']).' case(s): '.implode(', ', $result['cases']));
        $this->line($result['files'].' arquivo(s) de mídia.');

        foreach ($result['missing'] as $path) {
            $this->warn('Arquivo citado mas ausente no storage: '.$path);
        }

        $this->line('Arquivo: '.$result['path']);

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function list(string $option): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->option($option)))));
    }
}
