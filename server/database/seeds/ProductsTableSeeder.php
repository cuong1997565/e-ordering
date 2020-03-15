<?php

use App\Models\GradeGroup;
use App\Models\ProductType;
use App\Models\Uom;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Factory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $category = \App\Models\Category::all()->pluck('id')->toArray();

        $product_type = \App\Models\ProductType::all()->pluck('id')->toArray();


        $grade_group = \App\Models\GradeGroup::all()->pluck('id')->toArray();

        $year  = rand(2014, 2018);
        $month = rand(1, 12);
        $day   = rand(1, 28);
        $hour  = rand(7, 17);
        $minute = $faker->randomElement([00, 15, 30, 45]);
        $date   = Carbon::create($year, $month, $day, $hour, $minute, 0);

        $dataFeatureitem = \App\Models\FeatureItem::all()->pluck('id')->toArray();
        $dataGrade = \App\Models\Grade::all()->pluck('id')->toArray();
        $dataStore = \App\Models\Store::all()->pluck('id')->toArray();
        $arrayStore = $faker->randomElement($dataStore);
        $arrayFeatureitem = $faker->randomElement($dataFeatureitem);
        $dataPriceList [] = [
            'price_list_id' => 1,
            'grade_id' => $faker->randomElement($dataGrade),
            'unit_price' => 100000
        ];
        $starttime = microtime(true);
        for ($i = 1; $i <= 100; $i++) {
            $data = [];
                $count = ($i <= 10) ? '00'.$i : ($i < 99) ? '0'.$i : $i;
                array_push($data, [
                    'category_id' => $faker->randomElement($category),
                    'product_type_id' => $faker->randomElement($product_type),
                    'uom_id' =>  $faker->randomElement(['2','4']),
                    'grade_group_id' => $faker->randomElement($grade_group),
                    'short_name' => 'Sản phẩm '.$count,
                    'display_name' => 'Sản phẩm '.$count,
                    'is_life_management' => $faker->biasedNumberBetween(0,1),
                    'code'        => $count,
                    'name'        => 'Sản phẩm '.$count,
                    'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                            'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                    'active' => $faker->biasedNumberBetween(0,1),
                    'max_age' => $faker->biasedNumberBetween(1,20),
                    'release_date' =>$date->addDays(rand(1, 14))->format('Y-m-d'),
                ]);
                DB::table('products')->insert($data);
//                $product = Product::create($data[0]);
//                $product->featureitem()->sync($arrayFeatureitem);
//                $product->pricelistitem()->sync($dataPriceList);
//                $product->productstore()->sync($arrayStore);

            }
        $endtime = microtime(true);
        $timediff = $endtime - $starttime;
        echo $this->secondsToTime($timediff);

        ///////////////////////////////////////////////////////////
        $grade = array(1,2,3,4);
        $image = array('Product/y5qfH8rd6ZiXTkKLRCsrK1EdHdfcYxtdwqejK4LP.jpg','Product/xs24j8NEBSp7c5HH3400yXrltS8xdXMkOBvUswQH.jpg',
            'Product/BlLnj0hQhs5OuHS383VtTo9qGDYOjLK7U6xBq2dW.jpg', 'Product/z4wxGHyJff2ZwG6rUNo3tbU3uVFQ8mJNtSJZg0iL.jpg');
        $data = [
            [
              'category_id' => 12,
              'code' => '01.500500.02024',
              'name' => 'Gạch KT 500x500 mã 01.02024',
              'image' =>   'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                      'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
              'product_type_id' => 5,
              'uom_id' => 7,
              'grade_group_id' => 1,
              'short_name' =>'Gạch KT 500x500 mã 01.02024',
              'display_name' => 'Gạch KT 500x500 mã 01.02024',
              'is_life_management' => '1',
              'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.02863',
                'name' => 'Gạch KT 500x500 mã 01.02863',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 2,
                'short_name' =>'Gạch KT 500x500 mã 01.02863',
                'display_name' => 'Gạch KT 500x500 mã 01.02863',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.07855',
                'name' => 'Gạch KT 500x500 mã 01.07855',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 3,
                'short_name' =>'Gạch KT 500x500 mã 01.07855',
                'display_name' => 'Gạch KT 500x500 mã 01.07855',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.07856',
                'name' => 'Gạch KT 500x500 mã 01.07856',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' =>4,
                'short_name' =>'Gạch KT 500x500 mã 01.07856',
                'display_name' => 'Gạch KT 500x500 mã 01.07856',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09419',
                'name' => 'Gạch KT 500x500 mã 01.09419',
                'image' =>   'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 2,
                'short_name' =>'Gạch KT 500x500 mã 01.09419',
                'display_name' => 'Gạch KT 500x500 mã 01.09419',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09324',
                'name' => 'Gạch KT 500x500 mã 01.09324',
                'image' =>   'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' =>3,
                'short_name' =>'Gạch KT 500x500 mã 01.09324',
                'display_name' => 'Gạch KT 500x500 mã 01.09324',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],

            [
                'category_id' => 12,
                'code' => '01.500500.09420',
                'name' => 'Gạch KT 500x500 mã 01.09420',
                'image' =>   'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 2,
                'short_name' =>'Gạch KT 500x500 mã 01.09420',
                'display_name' => 'Gạch KT 500x500 mã 01.09420',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09427',
                'name' => '	Gạch KT 500x500 mã 01.09427',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09427',
                'display_name' => '	Gạch KT 500x500 mã 01.09427',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09429',
                'name' => '	Gạch KT 500x500 mã 01.09429',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09429',
                'display_name' => '	Gạch KT 500x500 mã 01.09429',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09430',
                'name' => '	Gạch KT 500x500 mã 01.09430',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09430',
                'display_name' => 'Gạch KT 500x500 mã 01.09430',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09435',
                'name' => '	Gạch KT 500x500 mã 01.09435',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09435',
                'display_name' => 'Gạch KT 500x500 mã 01.09435',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09438',
                'name' => '	Gạch KT 500x500 mã 01.09430',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09438',
                'display_name' => 'Gạch KT 500x500 mã 01.09438',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09451',
                'name' => '	Gạch KT 500x500 mã 01.09451',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09451',
                'display_name' => 'Gạch KT 500x500 mã 01.09451',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09452',
                'name' => '	Gạch KT 500x500 mã 01.09452',
                'image' =>  'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09452',
                'display_name' => 'Gạch KT 500x500 mã 01.09452',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09462',
                'name' => 'Gạch KT 500x500 mã 01.09462',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09462',
                'display_name' => 'Gạch KT 500x500 mã 01.09462',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09464',
                'name' => '	Gạch KT 500x500 mã 01.09464',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09464',
                'display_name' => 'Gạch KT 500x500 mã 01.09464',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09467',
                'name' => 'Gạch KT 500x500 mã 01.09467',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09467',
                'display_name' => 'Gạch KT 500x500 mã 01.09467',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],

            [
                'category_id' => 12,
                'code' => '01.500500.09468',
                'name' => '	Gạch KT 500x500 mã 01.09468',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 500x500 mã 01.09468',
                'display_name' => 'Gạch KT 500x500 mã 01.09468',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.400400.02201',
                'name' => 'Gạch KT 400x400 mã 01.02201',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => $faker->randomElement([1,2,3,4]),
                'short_name' =>'Gạch KT 400x400 mã 01.02201',
                'display_name' => 'Gạch KT 400x400 mã 01.02201',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09469',
                'name' => 'Gạch KT 500x500 mã 01.09469',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 1,
                'short_name' =>'Gạch KT 500x500 mã 01.09469',
                'display_name' => 'Gạch KT 500x500 mã 01.09469',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09470',
                'name' => 'Gạch KT 500x500 mã 01.09470',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 1,
                'short_name' =>'Gạch KT 500x500 mã 01.09470',
                'display_name' => 'Gạch KT 500x500 mã 01.09470',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09473',
                'name' => 'Gạch KT 500x500 mã 01.09473',
                'image' =>'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 4,
                'short_name' =>'Gạch KT 500x500 mã 01.09473',
                'display_name' => 'Gạch KT 500x500 mã 01.09473',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09475',
                'name' => 'Gạch KT 500x500 mã 01.09475',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 3,
                'short_name' =>'Gạch KT 500x500 mã 01.09475',
                'display_name' => 'Gạch KT 500x500 mã 01.09475',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09476',
                'name' => 'Gạch KT 500x500 mã 01.09476',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 2,
                'short_name' =>'Gạch KT 500x500 mã 01.09476',
                'display_name' => 'Gạch KT 500x500 mã 01.09476',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09478',
                'name' => 'Gạch KT 500x500 mã 01.09478',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 3,
                'short_name' =>'Gạch KT 500x500 mã 01.09478',
                'display_name' => 'Gạch KT 500x500 mã 01.09478',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.500500.09566',
                'name' => 'Gạch KT 500x500 mã 01.09566',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 2,
                'short_name' =>'Gạch KT 500x500 mã 01.09566',
                'display_name' => 'Gạch KT 500x500 mã 01.09566',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.400400.02002',
                'name' => 'Gạch KT 500x500 mã 01.09478',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 4,
                'short_name' =>'Gạch KT 400x400 mã 01.02002',
                'display_name' => 'Gạch KT 400x400 mã 01.02002',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.400400.02003',
                'name' => 'Gạch KT 400x400 mã 01.02003',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 3,
                'short_name' =>'Gạch KT 400x400 mã 01.02003',
                'display_name' => 'Gạch KT 400x400 mã 01.02003',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.400400.02005',
                'name' => 'Gạch KT 400x400 mã 01.02005',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 1,
                'short_name' =>'Gạch KT 400x400 mã 01.02005',
                'display_name' => 'Gạch KT 400x400 mã 01.02005',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
            [
                'category_id' => 12,
                'code' => '01.400400.02006',
                'name' => 'Gạch KT 400x400 mã 01.02006',
                'image' => 'Product/'.$faker->randomElement(['HIySG3WiJHF5QeUiIHy5qd8nHHftOMkQO9aAm4nL.jpg',
                        'oiwnzprWKSJ0Cew8gMMma8kgUu8yR66rhTjduazX.jpg','pTwGcdN7j8uESxvfnwqibbR3w3ejc7Sxc7LHSROU.jpg', 'HVPvXGax5L10EMKgKewMJ3WSe5JGWYp9Jk1Idzb6.jpg']),
                'product_type_id' => 5,
                'uom_id' => 7,
                'grade_group_id' => 2,
                'short_name' =>'Gạch KT 400x400 mã 01.02006',
                'display_name' => 'Gạch KT 400x400 mã 01.02006',
                'is_life_management' => '1',
                'max_age' => '13',
                'release_date' => '2018-03-13 00:00:00',
                'active' => 1
            ],
        ];

        DB::table('products')->insert($data); // Query Builder approach

    }
    public function secondsToTime($s)
    {
        $h = floor($s / 3600);
        $s -= $h * 3600;
        $m = floor($s / 60);
        $s -= $m * 60;
        return $h.':'.sprintf('%02d', $m).':'.sprintf('%02d', $s);
    }




}
