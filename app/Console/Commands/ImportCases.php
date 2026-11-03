<?php

namespace App\Console\Commands;

use App\Services\Cases\CaseTransfer;
use Illuminate\Console\Command;
use RuntimeException;

/**
 * Importa o .zip gerado por cases:export: cria ou atualiza cada case pelo slug e copia as mídias.
 * Case novo entra como rascunho; existente mantém o status, a menos que --publish seja usado.
 */
class ImportCases extends Command
{
    protected $signature = 'cases:import
        {file : Caminho do .zip gerado por cases:export}
        {--dry-run : Mostra o que seria feito, sem gravar}
        {--publish : Publica todos os cases importados}';

    protected $description = 'Importa cases exportados por cases:export.';

    public function handle(CaseTransfer $transfer): int
    {
        $dryRun = (bool) $this->option('dry-run');

        try {
            $result = $transfer->import((string) $this->argument('file'), $dryRun, (bool) $this->option('publish'));
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($dryRun) {
            $this->warn('Simulação: nada foi gravado.');
        }

        $this->table(
            ['Case', 'Ação', 'Status', 'Traduções'],
            array_map(fn (array $case): array => [$case['slug'], $case['action'], $case['status'], implode(', ', $case['translations']) ?: '-'], $result['cases']),
        );

        $files = $result['files'];
        $this->line($files['written'].' arquivo(s) '.($dryRun ? 'a gravar' : 'gravado(s)').', '.$files['skipped'].' já existente(s).');

        foreach ($files['renamed'] as $path) {
            $this->warn('Nome em uso com outro conteúdo; importado como '.$path);
        }

        return self::SUCCESS;
    }
}
