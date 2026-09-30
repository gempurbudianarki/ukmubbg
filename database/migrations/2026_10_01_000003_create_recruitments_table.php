<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code')->unique();
            $table->string('full_name');
            $table->string('nim');
            $table->string('email');
            $table->string('phone_whatsapp');
            $table->integer('semester');
            $table->string('class_group');
            $table->foreignId('first_choice_division_id')->constrained('divisions')->restrictOnDelete();
            $table->foreignId('second_choice_division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->text('reason_to_join');
            $table->string('portfolio_url')->nullable();
            $table->enum('status', ['pending', 'interview', 'accepted', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
