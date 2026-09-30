<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recruitments', function (Blueprint $table) {
            $table->enum('selection_stage', ['administrasi', 'wawancara', 'diterima', 'ditolak'])->default('administrasi')->after('status');
            $table->string('file_ktm')->nullable()->after('portfolio_url');
            $table->string('file_cv')->nullable()->after('file_ktm');
            $table->dateTime('interview_schedule')->nullable()->after('selection_stage');
            $table->string('interview_location')->nullable()->after('interview_schedule');
        });
    }

    public function down(): void
    {
        Schema::table('recruitments', function (Blueprint $table) {
            $table->dropColumn([
                'selection_stage',
                'file_ktm',
                'file_cv',
                'interview_schedule',
                'interview_location',
            ]);
        });
    }
};
