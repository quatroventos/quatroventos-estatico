<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SERVICES_OLD_TITLE = 'Serviços de IA, software, web e infraestrutura | Quatro Ventos';

    private const SERVICES_NEW_TITLE = 'Serviços de IA, software e infraestrutura | Quatro Ventos';

    /**
     * Canonical gravado que aponta para a própria página vira vazio: o site gera o canonical
     * com o domínio do APP_URL. Os valores herdados do site antigo apontavam para www, que não resolve mais.
     */
    public function up(): void
    {
        $prefixes = collect((array) config('locales.supported', []))
            ->map(fn ($meta) => trim((string) ($meta['prefix'] ?? ''), '/'));

        DB::table('pages')
            ->whereNotNull('canonical')
            ->where('canonical', '<>', '')
            ->get(['id', 'slug', 'locale', 'template', 'canonical'])
            ->each(function (object $page) use ($prefixes): void {
                $prefix = (string) ($prefixes[$page->locale] ?? '');
                $isHome = $page->template === 'home' || $page->slug === 'home';
                $own = '/'.trim($prefix.($isHome ? '' : '/'.$page->slug), '/');
                $path = '/'.trim((string) (parse_url((string) $page->canonical, PHP_URL_PATH) ?? ''), '/');

                if ($path === $own) {
                    DB::table('pages')->where('id', $page->id)->update(['canonical' => null]);
                }
            });

        DB::table('pages')
            ->where('slug', 'servicos')
            ->where('seo_title', self::SERVICES_OLD_TITLE)
            ->update(['seo_title' => self::SERVICES_NEW_TITLE]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->where('slug', 'servicos')
            ->where('seo_title', self::SERVICES_NEW_TITLE)
            ->update(['seo_title' => self::SERVICES_OLD_TITLE]);
    }
};
