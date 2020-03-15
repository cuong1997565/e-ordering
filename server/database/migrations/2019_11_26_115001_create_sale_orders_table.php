<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('so_number')->nullable();
            $table->integer('order_id');
            $table->integer('distributor_id');
            $table->integer('factory_id');
            $table->integer('price_list_id');
            $table->decimal('estimated_amount', 10, 2)->nullable();
            $table->dateTime('so_date')->nullable();
            $table->integer('sale_person_id');
            $table->tinyInteger('status');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('sale_orders');
    }
}
