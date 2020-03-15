<?php

use Illuminate\Database\Seeder;
use App\Models\SaleOrderItem;
class SaleOrderItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\SaleOrderItem::class, 50)->create();
    }
}
