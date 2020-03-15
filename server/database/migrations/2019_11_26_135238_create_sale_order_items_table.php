<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_order_id');
            $table->integer('product_id');
            $table->integer('grade_id');
            $table->longText('attributes')->nullable();
            $table->integer('uom_id');
            $table->decimal('sale_quantity',10,2)->nullable();
            $table->decimal('delivered_quantity',10,2)->nullable();
            $table->decimal('remaining_quantity',10,2)->nullable();
            $table->integer('unit_price_id')->nullable();
            $table->decimal('unit_price', 20, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->tinyInteger('status');
            $table->longText('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_order_items');
    }
}
