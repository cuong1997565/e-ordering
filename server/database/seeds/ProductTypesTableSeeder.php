<?php

use Illuminate\Database\Seeder;

use App\Models\ProductType;

class ProductTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ProductType::class, 50)->create();
    }
}
