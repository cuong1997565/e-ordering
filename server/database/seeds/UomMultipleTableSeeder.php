<?php

use Illuminate\Database\Seeder;
use App\Models\UomMultiple;

class UomMultipleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(UomMultiple::class, 50)->create();
    }
}
