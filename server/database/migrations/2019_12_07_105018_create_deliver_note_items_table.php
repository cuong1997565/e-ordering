<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverNoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_note_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_item_id');
            $table->integer('dn_id');
            $table->integer('store_id');
            $table->integer('product_id');
            $table->integer('grade_id');
            $table->longText('product_attributes')->nullable();
            $table->integer('uom_id');
            $table->decimal('deliver_quantity',10,2)->nullable();
            $table->integer('unit_price_id')->nullable();
            $table->decimal('unit_price', 20, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->tinyInteger('discount_type')->nullable();
            $table->decimal('amount_after_discount', 10, 2)->nullable();
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('deliver_note_items');
    }
}
