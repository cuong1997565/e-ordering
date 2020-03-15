<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function($table)
        {
            $table->datetime('canceled_date')->nullable()->change();
            $table->datetime('approved_date')->nullable()->change();
            $table->datetime('rejected_date')->nullable()->change();
            $table->datetime('completed_date')->nullable()->change();
            $table->datetime('processing_date')->nullable()->change();
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
