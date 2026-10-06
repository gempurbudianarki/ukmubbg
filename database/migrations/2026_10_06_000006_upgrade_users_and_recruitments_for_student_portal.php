<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Upgrade users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('member')->change();
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim', 30)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 25)->nullable()->after('nim');
            }
            if (!Schema::hasColumn('users', 'github_url')) {
                $table->string('github_url')->nullable()->after('phone_number');
            }
        });

        // 2. Upgrade recruitments table
        Schema::table('recruitments', function (Blueprint $table) {
            if (!Schema::hasColumn('recruitments', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('recruitments', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('phone_whatsapp');
            }
            if (!Schema::hasColumn('recruitments', 'github_url')) {
                $table->string('github_url')->nullable()->after('profile_photo');
            }
            $table->string('class_group')->nullable()->change();
        });

        // 3. Upgrade members table
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('members', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('members', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });

        Schema::table('recruitments', function (Blueprint $table) {
            if (Schema::hasColumn('recruitments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('recruitments', 'profile_photo')) {
                $table->dropColumn('profile_photo');
            }
            if (Schema::hasColumn('recruitments', 'github_url')) {
                $table->dropColumn('github_url');
            }
            $table->string('class_group')->nullable(false)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'github_url')) {
                $table->dropColumn('github_url');
            }
            if (Schema::hasColumn('users', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('users', 'nim')) {
                $table->dropColumn('nim');
            }
        });
    }
};
