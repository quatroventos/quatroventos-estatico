<?php

namespace App\Services\Mockup;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use RuntimeException;

class DeviceScreenshot
{
    public function capture(string $url, string $device): string
    {
        $viewport = MockupCatalog::viewports()[$device] ?? null;

        if ($viewport === null) {
            throw new RuntimeException('Dispositivo inválido.');
        }

        $binary = $this->chromeBinary();
        $directory = storage_path('app/mockup-captures');
        File::ensureDirectoryExists($directory);
        $profile = $directory.'/'.Str::uuid()->toString();
        $screenshot = $profile.'.png';
        File::ensureDirectoryExists($profile);

        try {
            $result = Process::timeout(40)->run([
                $binary,
                '--headless=new',
                '--disable-gpu',
                '--hide-scrollbars',
                '--no-first-run',
                '--no-default-browser-check',
                '--disable-extensions',
                '--disable-background-networking',
                '--user-data-dir='.$profile,
                '--user-agent='.$viewport['user_agent'],
                '--force-device-scale-factor='.$viewport['scale'],
                '--window-size='.$viewport['width'].','.$viewport['height'],
                '--virtual-time-budget=12000',
                '--timeout=25000',
                '--screenshot='.$screenshot,
                $url,
            ]);

            $bytes = is_file($screenshot) ? (string) file_get_contents($screenshot) : '';

            if (! str_starts_with($bytes, "\x89PNG")) {
                $detail = trim($result->errorOutput()."\n".$result->output());

                throw new RuntimeException(
                    'O Chrome não devolveu a print.'
                    .($detail !== '' ? ' '.mb_substr($detail, 0, 240) : '')
                );
            }

            return $bytes;
        } finally {
            File::deleteDirectory($profile);
            if (is_file($screenshot)) {
                unlink($screenshot);
            }
        }
    }

    private function chromeBinary(): string
    {
        $configured = config('services.mockup.chrome_binary');

        if (is_string($configured) && $configured !== '') {
            if (! is_executable($configured)) {
                throw new RuntimeException('Não achei o Chrome para capturar a página.');
            }

            return $configured;
        }

        $candidates = [
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
        ];

        foreach ($candidates as $path) {
            if (is_executable($path)) {
                return $path;
            }
        }

        throw new RuntimeException('Não achei o Chrome para capturar a página.');
    }
}
