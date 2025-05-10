<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Adding missing columns to the sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('customer_id'); // Adding discount to sales
        });
        
    }

    public function down()
    {
        // Rollback the changes
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('discount');
        });       
    }
};
