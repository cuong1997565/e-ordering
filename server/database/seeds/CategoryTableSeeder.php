<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $shops = [
//            [
//                'name' => 'Books',
//                'children' => [
//                    [
//                        'name' => 'Comic Book',
//                        'children' => [
//                            ['name' => 'Marvel Comic Book'],
//                            ['name' => 'DC Comic Book'],
//                            ['name' => 'Action comics'],
//                        ],
//                    ],
//                    [
//                        'name' => 'Textbooks',
//                        'children' => [
//                            ['name' => 'Business'],
//                            ['name' => 'Finance'],
//                            ['name' => 'Computer Science'],
//                        ],
//                    ],
//                ],
//            ],
//            [
//                'name' => 'Electronics',
//                'children' => [
//                    [
//                        'name' => 'TV',
//                        'children' => [
//                            ['name' => 'LED'],
//                            ['name' => 'Blu-ray'],
//                        ],
//                    ],
//                    [
//                        'name' => 'Mobile',
//                        'children' => [
//                            ['name' => 'Samsung'],
//                            ['name' => 'iPhone'],
//                            ['name' => 'Xiomi'],
//                        ],
//                    ],
//                ],
//            ],
//        ];
//        foreach($shops as $shop)
//        {
//            \App\Models\Category::create($shop);
////            dd($shop);
//        }


        $faker = Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            Category::create([
                'parent_id' => 0,
                'name' => $faker->name,
                'order' => $faker->biasedNumberBetween(0, 4),
                'active' => $faker->biasedNumberBetween(0, 1),
                'level' => 1,
                'code' => $faker->uuid

            ]);
        }

         $category = Category::all()->pluck('id')->toArray();
         for ($i = 0; $i < 30; $i++) {
            Category::create([
                'parent_id' => $faker->randomElement($category),
                'name' => $faker->name,
                'order' => $faker->biasedNumberBetween(0, 4),
                'active' => $faker->biasedNumberBetween(0, 1),
                'level' => 2,
                'code' => $faker->uuid
            ]);
        }
    }
}
