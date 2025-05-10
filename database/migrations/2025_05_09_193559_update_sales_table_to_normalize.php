<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSalesTableToNormalize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Drop unnecessary columns from 'sales' table
            $table->dropColumn(['subtotal', 'discount']); // Remove discount and subtotal columns from sales table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // If you need to rollback, add these columns back to the 'sales' table
            $table->decimal('subtotal', 10, 2)->after('total');
            $table->decimal('discount', 5, 2)->nullable()->after('subtotal');
        });
    }
}
