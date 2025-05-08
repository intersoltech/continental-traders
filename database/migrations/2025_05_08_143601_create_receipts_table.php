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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('sale'); // could be sale, estimate, return, etc.
            $table->unsignedBigInteger('reference_id'); // could point to sales or estimates
            $table->string('reference_type'); // 'sale' or 'estimate'
            $table->string('receipt_number')->unique();
            $table->date('date');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
