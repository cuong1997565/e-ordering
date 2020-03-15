<?php

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class, 150)->create();
    }
}
