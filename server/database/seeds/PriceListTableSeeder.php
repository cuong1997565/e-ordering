<?php

use Illuminate\Database\Seeder;
use App\Models\PriceList;

class PriceListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PriceList::class, 50)->create();
    }
}
