<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('grade_id');
            $table->longText('attributes')->nullable();
            $table->integer('uom_id');
            $table->decimal('sale_quantity',10,2)->nullable();
            $table->decimal('delivery_quantity',10,2)->nullable();
            $table->decimal('remaining_quantity',10,2)->nullable();
            $table->integer('distributor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            //
        });
    }
}
