<?php

use Illuminate\Database\Seeder;
use App\Models\DistributorProduct;

class DistributorProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DistributorProduct::class, 150)->create();
    }
}
