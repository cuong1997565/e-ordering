<?php

use Illuminate\Database\Seeder;
use App\Models\Attributes;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Attributes::class,50)->create();
    }
}
