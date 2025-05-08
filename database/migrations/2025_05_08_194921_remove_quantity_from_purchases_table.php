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
        // Remove the quantity column from purchases table
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback the migration by adding the quantity column back
        Schema::table('purchases', function (Blueprint $table) {
            $table->integer('quantity')->default(0);
        });
    }
};
