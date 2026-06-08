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
        Schema::create('master_checksheet_headers', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_category', 255)->unique();
            $table->boolean('is_active')->default(true);
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->softDeletes();
        });

        Schema::create('master_checksheet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_id')->constrained('master_checksheet_headers')->cascadeOnDelete();
            $table->integer('order')->default(1);
            $table->string('name', 150);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_checksheet_details');
        Schema::dropIfExists('master_checksheet_headers');
    }
};
