<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim', 30);
            $table->string('period', 20)->default('2026/2027');
            $table->enum('department_level', ['bph', 'pemrograman', 'multimedia', 'iot', 'cyber']);
            $table->string('position');
            $table->string('photo')->nullable();
            $table->json('social_links')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
