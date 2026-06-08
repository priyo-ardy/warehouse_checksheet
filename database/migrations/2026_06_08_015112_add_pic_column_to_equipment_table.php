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
        Schema::table('equipment', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->after('id');
            $table->foreignId('leader_id')->nullable()->after('serial_no')->constrained('users')->restrictOnDelete();
            $table->text('remark')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
            $table->dropColumn(['image', 'leader_id', 'remark']);
        });
    }
};
