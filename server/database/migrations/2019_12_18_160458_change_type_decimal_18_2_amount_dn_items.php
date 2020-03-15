<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeDecimal182AmountDnItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_note_items', function($table)
        {
            $table->decimal('amount', 18, 2)->nullable()->change();
            $table->decimal('amount_after_discount', 18, 2)->nullable()->change();
            $table->decimal('discount', 18, 2)->nullable()->change();
        });
        Schema::table('sale_order_items', function($table)
        {
            $table->decimal('amount', 18, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
