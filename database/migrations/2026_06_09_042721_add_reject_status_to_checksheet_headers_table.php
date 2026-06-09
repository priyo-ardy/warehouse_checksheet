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
        Schema::table('checksheet_headers', function (Blueprint $table) {
            $table->string('doc_status', 20)->after('code')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('rejected_date')->nullable();
            $table->text('rejected_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checksheet_headers', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);

            $table->dropColumn(['doc_status', 'rejected_by', 'rejected_date', 'rejected_reason']);
        });
    }
};
