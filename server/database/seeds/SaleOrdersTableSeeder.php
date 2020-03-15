<?php

use App\Models\SaleOrder;
use Illuminate\Database\Seeder;

class SaleOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        //factory(\App\Models\SaleOrder::class, 50)->create();

        factory(App\Models\SaleOrder::class, 50)->create()->each(function ($sale) {
            $sale->sale_order_items()->save(factory(App\Models\SaleOrderItem::class)->make());
        });

    }
}
