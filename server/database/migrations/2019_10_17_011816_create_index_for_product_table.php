<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexForProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['factory_id', 'brand_id', 'category_id', 'code']);
            $table->index(['factory_id', 'category_id', 'brand_id', 'code']);
            $table->index(['category_id', 'brand_id', 'code']);
            $table->index(['brand_id', 'category_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('index_for_product');
    }
}
