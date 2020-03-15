<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAddFieldDateOrder extends Migration
{
    public function up()
    {
        Schema::table('orders', function($table)
        {
            $table->datetime('canceled_date');
            $table->datetime('approved_date');
            $table->datetime('rejected_date');
            $table->datetime('completed_date');
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
