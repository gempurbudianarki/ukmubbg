<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline');
            $table->text('description');
            $table->text('focus_topics');
            $table->text('icon_svg');
            $table->string('color_accent')->default('#2563eb');
            $table->string('banner_image')->nullable();
            $table->text('vision');
            $table->text('mission');
            $table->string('adviser_name');
            $table->string('adviser_title');
            $table->string('adviser_photo')->nullable();
            $table->string('leader_name');
            $table->string('leader_nim');
            $table->string('leader_photo')->nullable();
            $table->text('leader_bio');
            $table->text('social_links')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
