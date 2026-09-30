<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_code', 50)->unique();
            $table->string('recipient_name');
            $table->string('recipient_nim', 30)->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('event_name');
            $table->string('role_as', 100)->default('Peserta');
            $table->date('issue_date');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
