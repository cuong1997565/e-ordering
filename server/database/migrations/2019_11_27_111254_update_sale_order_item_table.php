<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSaleOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_order_items', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->dropColumn('attributes');
            $table->longText('user_note')->nullable();
            $table->longText('sale_note')->nullable();
            $table->integer('status')->default(0)->change();
            $table->longText('product_attributes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_order_items', function (Blueprint $table) {
            //
        });
    }
}
