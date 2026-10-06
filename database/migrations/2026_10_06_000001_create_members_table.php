<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->nullable()->constrained('recruitments')->nullOnDelete();
            $table->string('nim', 20)->unique()->index();
            $table->string('name');
            $table->string('email');
            $table->string('phone_number', 25)->nullable();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->string('batch_year', 10)->default('2026');
            $table->enum('status', ['aktif', 'non_aktif', 'alumni'])->default('aktif');
            $table->date('join_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
