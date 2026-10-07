<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->string('passcode', 50)->nullable()->after('status');
            $table->boolean('allow_self_checkin')->default(true)->after('passcode');
        });

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->string('checkin_type', 20)->default('admin')->after('status'); // admin | self
            $table->timestamp('checked_in_at')->nullable()->after('checkin_type');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn(['passcode', 'allow_self_checkin']);
        });

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropColumn(['checkin_type', 'checked_in_at']);
        });
    }
};
