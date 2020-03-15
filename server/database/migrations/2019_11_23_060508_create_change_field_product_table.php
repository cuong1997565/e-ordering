<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeFieldProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function($table)
        {
             $table->dropColumn('factory_id');
             $table->dropColumn('brand_id');
             $table->dropColumn('price');
             $table->dropColumn('extra_address');
             $table->dropColumn('parent_category');
             $table->integer('product_type_id')->unsigned();
             $table->integer('uom_id')->unsigned();
             $table->integer('grade_group_id');
             $table->string('short_name');
             $table->string('display_name');
             $table->boolean('is_life_management');
             $table->integer('max_age')->default(0);
             $table->datetime('release_date');
             $table->boolean('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
