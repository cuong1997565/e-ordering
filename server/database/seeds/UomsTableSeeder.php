<?php

use Illuminate\Database\Seeder;
use App\Models\Uom;

class UomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [];
        //create uom is_based_uom = true
        for ($i = 1; $i <= 10; $i++) {
                array_push($data, [
                 'name' => $faker->name,
                 'code' => $faker->uuid,
                 'display_name' => $faker->name,
                 'description' => $faker->realText(rand(10, 60)),
                 'is_based_uom' => true,
                 'conversion_rate' => $faker->biasedNumberBetween(0, 10),
                 'isrounded' => $faker->biasedNumberBetween(false, true),
                 'round_priority' => $faker->biasedNumberBetween(1,3),
                ]);
        }
        DB::table('uoms')->insert($data);
        $uom = \App\Models\Uom::all()->pluck('id')->toArray();
        //create based_uom_id
        $baseUomId = [];
        for ($i = 1; $i <= 30; $i++) {
            array_push($baseUomId, [
                'name' => $faker->name,
                'code' => $faker->uuid,
                'display_name' => $faker->name,
                'description' => $faker->realText(rand(10, 60)),
                'is_based_uom' => false,
                'conversion_rate' => $faker->biasedNumberBetween(0, 10),
                'isrounded' => $faker->biasedNumberBetween(false, true),
                'round_priority' => $faker->biasedNumberBetween(1,3),
                'based_uom_id' => $faker->randomElement($uom)
            ]);
        }
        DB::table('uoms')->insert($baseUomId);

    }
}
