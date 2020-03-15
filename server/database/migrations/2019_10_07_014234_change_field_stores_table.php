<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function($table) {
            $table->dropColumn('dimi_store_id');
            $table->dropColumn('area_id');
            $table->dropColumn('distributor_id');
            $table->dropColumn('limit_to');
            $table->dropColumn('dimi_email');
            $table->dropColumn('dimi_phone');
            $table->dropColumn('dimi_address');
            $table->string('dimi_code');
            $table->integer('dimi_factory')->unsigned();

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
