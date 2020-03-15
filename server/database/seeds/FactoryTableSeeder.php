<?php

use Illuminate\Database\Seeder;

class FactoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Factory::class, 50)->create();
    }
}
