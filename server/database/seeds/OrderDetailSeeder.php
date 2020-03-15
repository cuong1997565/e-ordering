<?php

use Illuminate\Database\Seeder;
use App\Models\OrderProduct;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\OrderProduct::class, 50)->create();
    }
}
