<?php

use Illuminate\Database\Seeder;
use App\Models\DeliveryNote;

class DeliveryNotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DeliveryNote::class, 100)->create();
    }
}
