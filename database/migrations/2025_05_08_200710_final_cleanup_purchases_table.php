<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinalCleanupPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove unnecessary columns from the purchases table
        Schema::table('purchases', function (Blueprint $table) {
            // Remove columns now handled in purchase_items table
            $table->dropColumn(['cost_price', 'warranty_months']);
        });       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // If rolling back, we can add the columns back with their default values
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->integer('warranty_months')->nullable();
        });
    }
}
