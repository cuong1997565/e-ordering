<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Distributor;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Distributor::class, function (Faker $faker) {
    $area = \App\Models\Area::all()->pluck('id')->toArray();
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'address' => $faker->address,
        'phone' => $faker->biasedNumberBetween(0, 10),
        'area_id' => $faker->randomElement($area),
        'code' => $faker->uuid,
        'tax_code' => $faker->uuid,
        'contact_person' => $faker->name,
        'active' => $faker->biasedNumberBetween(0, 1),
    ];
});
