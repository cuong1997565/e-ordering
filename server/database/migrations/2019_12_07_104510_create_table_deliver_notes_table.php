<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDeliverNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dn_number', 124)->unique();
            $table->integer('distributor_id');
            $table->integer('factory_id');
            $table->string('erp_so_number')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_after_discount', 10, 2);
            $table->integer('sale_person_id');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('delivery_notes');
    }
}
