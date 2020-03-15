<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Base extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('email');
            $table->string('username');
            $table->string('roles')->nullable();
            $table->string('name');
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('auth_token')->nullable();
            $table->dateTime('last_action')->nullable();
            $table->string('extra_token')->nullable();
            $table->tinyInteger('group')->default(1);
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('gender')->nullable();
            $table->timestamps();
        });

        \App\Models\User::create
        ([
            'email' => 'test@grooo.vn',
            'name' => 'Test Administrator',
            'username' => 'admin',
            'roles' => 'system_admin',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'group' => GROUP_ADMIN,
            'active' => ACTIVE_TRUE
        ]);


        /* ---------- Sample tables { ---------- */

        Schema::create('sample_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });

        \App\Models\SampleType::create(['name' => 'Nokia', 'active' => ACTIVE_TRUE]);
        \App\Models\SampleType::create(['name' => 'Samsung', 'active' => ACTIVE_TRUE]);

        // samples
        Schema::create('samples', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sample_type_id');
            $table->string('name', 255);
            $table->string('image', 255)->nullable();
            $table->string('avatar', 1000)->nullable();
            $table->string('gallery', 1000)->nullable();
            $table->string('description', 2000)->nullable();
            $table->text('content_en')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });

        // sample_items
        Schema::create('sample_items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sample_id');
            $table->string('name', 255);
            $table->integer('number')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });

        /* ---------- Sample tables } ---------- */

        Schema::create('mail_templates', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->text('subject');
            $table->text('content');
            $table->string('variable', 1000);
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
