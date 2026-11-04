<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Texto editado no painel. Nulo mantém o arquivo gerado automaticamente.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('robots_txt')->nullable()->after('sitemap_extra');
            $table->text('llms_txt')->nullable()->after('robots_txt');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['robots_txt', 'llms_txt']);
        });
    }
};
