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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('checksheet_headers')->cascadeOnDelete();
            $table->integer('order');
            $table->boolean('status')->nullable()->default(null);
            $table->foreignId('approver')->constrained('users')->restrictOnDelete();
            $table->timestamp('approved_date')->nullable()->default(null);
            $table->boolean('last_approver')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
