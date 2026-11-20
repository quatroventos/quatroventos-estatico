<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_visibility_reports', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 32)->unique();
            $table->string('url', 500);
            $table->string('host', 255)->index();
            $table->unsignedTinyInteger('score');
            $table->string('band', 16);
            $table->string('niche', 255)->nullable();
            $table->json('checks');
            $table->json('fixes');
            $table->unsignedInteger('response_ms')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_visibility_reports');
    }
};
