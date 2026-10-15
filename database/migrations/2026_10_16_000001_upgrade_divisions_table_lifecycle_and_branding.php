<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            if (!Schema::hasColumn('divisions', 'status')) {
                $table->enum('status', ['active', 'suspended', 'archived'])->default('active')->after('social_links');
            }
            if (!Schema::hasColumn('divisions', 'icon_class')) {
                $table->string('icon_class', 60)->default('fa-laptop-code')->after('icon_svg');
            }
            if (!Schema::hasColumn('divisions', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }

            // Make metadata fields nullable to support lean dynamic creation
            $table->string('tagline')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->text('focus_topics')->nullable()->change();
            $table->text('icon_svg')->nullable()->change();
            $table->text('vision')->nullable()->change();
            $table->text('mission')->nullable()->change();
            $table->string('adviser_name')->nullable()->change();
            $table->string('adviser_title')->nullable()->change();
            $table->string('leader_name')->nullable()->change();
            $table->string('leader_nim')->nullable()->change();
            $table->text('leader_bio')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            if (Schema::hasColumn('divisions', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('divisions', 'icon_class')) {
                $table->dropColumn('icon_class');
            }
            if (Schema::hasColumn('divisions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
