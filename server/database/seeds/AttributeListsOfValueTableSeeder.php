<?php

use Illuminate\Database\Seeder;
use App\Models\AttributeListsOfValue;
class AttributeListsOfValueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         factory(AttributeListsOfValue::class,30)->create();
    }
}
