<?php

use App\Cms\PrivacyPolicyAiVisibilityPatch;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        PrivacyPolicyAiVisibilityPatch::apply();
    }

    public function down(): void
    {
        //
    }
};
