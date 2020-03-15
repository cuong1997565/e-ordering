<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_types', function (Blueprint $table) {
            $table->increments('id');
            $table->text('code')->nullable();
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->boolean('is_percentage')->nullable();
            $table->boolean('is_custom_rate')->nullable();
            $table->boolean('is_stack_discount')->nullable();
            $table->decimal('discount_rate', 10, 2)->nullable();
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
        Schema::dropIfExists('discount_types');
    }
}
