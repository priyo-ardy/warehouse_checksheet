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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar', 255)->after('id')->nullable();
            $table->string('phone', 25)->after('email')->nullable();
            $table->integer('login_attempts')->after('password')->default(0);
            $table->boolean('is_locked')->after('login_attempts')->default(false);
            $table->dateTime('last_login')->after('is_locked')->nullable();
            $table->string('last_login_from', 25)->after('last_login')->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_active')->after('last_login_from')->default(true);
            $table->integer('role')->after('is_active')->default(0);
            $table->text('remark')->nullable()->after('role');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'phone',
                'login_attempts',
                'is_locked',
                'last_login',
                'last_login_from',
                'user_agent',
                'is_active',
                'role',
                'remark'
            ]);
        });
    }
};
