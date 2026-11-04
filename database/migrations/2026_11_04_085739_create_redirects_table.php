<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mapa que vivia em config/redirects.php: site antigo e slugs renomeados.
     */
    private const LEGACY = [
        '/index' => '/',
        '/criacao-de-sites' => '/criacao-de-sites-sob-medida',
        '/desenvolvimento-de-sites' => '/criacao-de-sites-sob-medida',
        '/desenvolvimento-de-landing-page' => '/criacao-de-landing-pages',
        '/criar-landing-page' => '/criacao-de-landing-pages',
        '/criar-landing-pages' => '/criacao-de-landing-pages',
        '/landing-pages' => '/criacao-de-landing-pages',
        '/portfolio-landing-page' => '/criacao-de-landing-pages',
        '/desenvolvimento-de-ecommerce' => '/criacao-de-loja-virtual',
        '/ecommerce' => '/criacao-de-loja-virtual',
        '/desenvolvimento-de-sites-wordpress' => '/criacao-de-site-wordpress',
        '/sites-wordpress' => '/criacao-de-site-wordpress',
        '/recuperacao-de-site-wordpress' => '/site-wordpress-hackeado',
        '/manutencao-de-sites-wordpress' => '/manutencao-de-sites',
        '/contratar-manutencao' => '/manutencao-de-sites',
        '/vps-e-infraestrutura' => '/vps-gerenciado',
        '/desenvolvimento-full-stack' => '/software-sob-medida',
        '/automacao-com-ia' => '/automacao-de-processos-com-ia-e-n8n',
        '/inteligencia-artificial' => '/inteligencia-artificial-para-empresas',
        '/ia-com-dados-da-empresa' => '/rag',
        '/sistemas-com-ia' => '/sistemas-com-inteligencia-artificial',
        '/sistemas-com-inteligncia-artificial' => '/sistemas-com-inteligencia-artificial',
        '/modernizacao-de-sistemas' => '/modernizacao-de-sistemas-legados',
        '/modernizao-de-sistemas-legados' => '/modernizacao-de-sistemas-legados',
        '/seo-e-llms' => '/geo-seo',
        '/sobre' => '/sobre-a-quatro-ventos',
        '/porque-meu-site-nao-vende' => '/diagnostico-de-site',
        '/signup' => '/contato',
        '/contrato' => '/contato',
        '/portfolio' => '/cases',
        '/novo-portfolio' => '/cases',
        '/portfolio-new' => '/cases',
        '/obrigado-analise' => '/obrigado',
        '/obrigado_host' => '/obrigado',
    ];

    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source', 500)->unique();
            $table->string('target', 500);
            $table->boolean('automatic')->default(false);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('redirects')->insert(array_map(fn (string $source, string $target) => [
            'source' => $source,
            'target' => $target,
            'automatic' => false,
            'hits' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ], array_keys(self::LEGACY), self::LEGACY));
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
