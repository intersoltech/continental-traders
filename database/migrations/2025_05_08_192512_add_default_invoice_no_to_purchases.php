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
        Schema::table('purchases', function (Blueprint $table) {
            // Add a default value for invoice_no if necessary
            $table->string('invoice_no')->default(DB::raw('CONCAT("INV-", DATE_FORMAT(NOW(), "%Y%m%d%H%i%s"))'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Roll back to previous state if needed
            $table->string('invoice_no')->nullable(false)->change();
        });
    }
};
