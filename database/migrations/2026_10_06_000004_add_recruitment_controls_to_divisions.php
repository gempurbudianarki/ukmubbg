<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->boolean('is_recruitment_open')->default(true)->after('social_links');
            $table->integer('recruitment_quota')->nullable()->after('is_recruitment_open');
            $table->string('recruitment_notes')->nullable()->after('recruitment_quota');
        });
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn(['is_recruitment_open', 'recruitment_quota', 'recruitment_notes']);
        });
    }
};
