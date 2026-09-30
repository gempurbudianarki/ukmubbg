<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('banner_image')->nullable();
            $table->date('event_date');
            $table->time('time_start');
            $table->time('time_end')->nullable();
            $table->enum('location_type', ['offline', 'online', 'hybrid'])->default('offline');
            $table->string('location_venue');
            $table->string('registration_link')->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->enum('status', ['upcoming', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
