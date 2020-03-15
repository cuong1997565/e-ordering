<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUomMultiplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uom_multiples', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uom_id')->unsigned();
            $table->string('code');
            $table->string('name');
            $table->string('display_name');
            $table->string('description');
            $table->string('conversion_rate');
            $table->boolean('isrounded');
            $table->integer('round_priority');
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
        Schema::dropIfExists('uom_multiples');
    }
}
