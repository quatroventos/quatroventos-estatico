<?php

use App\Cms\EnsureAiVisibilityCmsPage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        config(['cms.og_on_save' => false]);
        EnsureAiVisibilityCmsPage::apply(force: true);
    }

    public function down(): void
    {
        //
    }
};
