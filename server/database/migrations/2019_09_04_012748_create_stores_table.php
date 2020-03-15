<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dimi_store_id');
            $table->integer('area_id')->unsigned();
            $table->integer('distributor_id')->unsigned();
            $table->double('limit');
            $table->string('dimi_name');
            $table->string('dimi_email');
            $table->string('dimi_phone');
            $table->string('dimi_address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
