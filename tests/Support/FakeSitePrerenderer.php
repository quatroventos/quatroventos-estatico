<?php

namespace Tests\Support;

use App\Services\SitePrerenderer;
use Illuminate\Support\Facades\File;

/**
 * Substitui o Node nos testes: grava um fragmento mínimo no caminho real e lembra o que criou,
 * para o TestCase apagar no fim e não deixar HTML de teste no storage do ambiente local.
 */
class FakeSitePrerenderer extends SitePrerenderer
{
    /** @var list<string> */
    public array $created = [];

    public function render(array $payloads, int $timeout = 600, ?callable $output = null): void
    {
        foreach ($payloads as $payload) {
            $file = self::fileFor($payload['page']);

            if (is_file($file)) {
                continue;
            }

            File::ensureDirectoryExists(dirname($file));
            File::put($file, '<main id="conteudo"></main>');
            $this->created[] = $file;
        }
    }

    public function cleanup(): void
    {
        foreach ($this->created as $file) {
            File::delete($file);
        }

        $this->created = [];
    }
}
