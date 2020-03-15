<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class Cms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //menus
        Schema::create('menus', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('type')->default(0);
            $table->integer('type_internal')->default(1);
            $table->string('link', 1000)->nullable();
            $table->tinyInteger('order')->nullable()->default(0);
            $table->tinyInteger('open_tab')->nullable()->default(0);
            $table->tinyInteger('active')->nullable()->default(1);
            NestedSet::columns($table);
            $table->timestamps();
        });

        //posts
        Schema::create('posts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('title');
            $table->text('slug');
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('view')->nullable()->default(0);
            $table->tinyInteger('order')->nullable()->default(0);
            $table->integer('user_id');
            $table->tinyInteger('active')->nullable()->default(1);
            $table->timestamps();
        });

        //categories_posts
        Schema::create('categories_posts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('post_id');
            $table->integer('order')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('phone_number');
            $table->string('email');
            $table->text('content');
            $table->timestamps();
        });

        //languages
        Schema::create('langs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('lang', 255);
            $table->timestamps();
        });
        \App\Models\Lang::create(['lang' => 'vn']);
        \App\Models\Lang::create(['lang' => 'jp']);
        \App\Models\Lang::create(['lang' => 'cn']);
        \App\Models\Lang::create(['lang' => 'kn']);

        //translate
        Schema::create('translations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('key');
            $table->string('lang', 255);
            $table->text('trans')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->timestamps();
        });

        // Static content
        Schema::create('static_contents', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('code')->unique();
            $table->text('description');
            $table->text('content');
            $table->tinyInteger('active')->nullable()->default(0);
            $table->timestamps();
        });

        //Translate content
        Schema::create('translation_contents', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('table', 255);
            $table->integer('table_id');
            $table->string('table_field', 255);
            $table->string('lang',255);
            $table->text('trans');
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
        //
    }
}
