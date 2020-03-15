<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeFieldUomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uoms', function($table)
        {
            $table->boolean('is_based_uom');
            $table->boolean('isrounded');
            $table->string('conversion_rate');
            $table->integer('round_priority');
            $table->integer('based_uom_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_field_uoms');
    }
}
