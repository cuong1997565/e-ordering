<?php

use App\Models\ProductStore;
use Illuminate\Database\Seeder;

class ProductStoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ProductStore::class, 30)->create();
    }
}
