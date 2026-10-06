<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->string('day_name', 20)->default('Senin')->after('title');
            $table->string('session_type', 30)->default('riset_rutin')->after('time_end');
            $table->string('topic_material', 255)->nullable()->after('location');
            $table->text('learning_outcomes')->nullable()->after('topic_material');
            $table->string('instructor_name', 150)->nullable()->after('learning_outcomes');
            $table->enum('status', ['open', 'closed'])->default('open')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'day_name',
                'session_type',
                'topic_material',
                'learning_outcomes',
                'instructor_name',
                'status',
            ]);
        });
    }
};
