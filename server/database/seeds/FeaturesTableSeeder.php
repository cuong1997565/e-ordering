<?php

use Illuminate\Database\Seeder;

use App\Models\Features;

class FeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Features::class, 50)->create();

    }
}
