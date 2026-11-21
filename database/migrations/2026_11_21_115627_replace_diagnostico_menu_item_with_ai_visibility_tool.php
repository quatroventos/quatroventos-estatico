<?php

use App\Models\AiVisibilityReport;
use App\Models\MenuItem;
use App\Models\Page;
use App\Support\Locales;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $href = '/'.AiVisibilityReport::PATH;
        $pageId = Page::query()
            ->where('slug', AiVisibilityReport::PATH)
            ->where('locale', Locales::default())
            ->value('id');

        MenuItem::query()
            ->where('href', '/diagnostico-de-site')
            ->where(function ($query): void {
                $query
                    ->where('label', 'Diagnóstico gratuito de site')
                    ->orWhereIn('label', [
                        'Free site diagnosis',
                        'Free website audit',
                        'Diagnóstico gratuito',
                    ]);
            })
            ->get()
            ->each(function (MenuItem $item) use ($href, $pageId): void {
                $item->fill([
                    'label' => 'Teste se a IA lê o seu site',
                    'href' => $href,
                    'page_id' => $pageId,
                ]);
                $item->save();
            });
    }

    public function down(): void
    {
        $pageId = Page::query()
            ->where('slug', 'diagnostico-de-site')
            ->where('locale', Locales::default())
            ->value('id');

        MenuItem::query()
            ->where('href', '/'.AiVisibilityReport::PATH)
            ->where('label', 'Teste se a IA lê o seu site')
            ->get()
            ->each(function (MenuItem $item) use ($pageId): void {
                $item->fill([
                    'label' => 'Diagnóstico gratuito de site',
                    'href' => '/diagnostico-de-site',
                    'page_id' => $pageId,
                ]);
                $item->save();
            });
    }
};
