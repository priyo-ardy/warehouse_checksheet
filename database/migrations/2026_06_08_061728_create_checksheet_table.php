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
        Schema::create('checksheet_headers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->date('tanggal');
            $table->string('shift', 1)->nullable();
            $table->foreignId('equipment_id')->constrained('equipment')->restrictOnDelete();
            $table->foreignId('leader_id')->constrained('users')->restrictOnDelete();
            $table->text('remark')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_closed')->default(false);
            $table->boolean('leader_approve')->default(false);
            $table->foreignId('leader_approve_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('leader_approve_date')->nullable();
            $table->boolean('spv_approve')->default(false);
            $table->foreignId('spv_approve_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('spv_approve_date')->nullable();
            $table->boolean('she_approve')->default(false);
            $table->foreignId('she_approve_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('she_approve_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('checksheet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_id')->constrained('checksheet_headers')->cascadeOnDelete();
            $table->integer('order')->default(1);
            $table->foreignId('item_id')->constrained('master_checksheet_details')->restrictOnDelete();
            $table->string('parameter_name_snapshot', 150);
            $table->string('status', 10)->default('OK');
            $table->string('actual_value')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('checksheet_details');
        Schema::dropIfExists('checksheet_headers');
    }
};
