<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Area;
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

$factory->define(Area::class, function (Faker $faker) {
    return [
        'parent_id' => $faker->uuid,
        'name' => $faker->name,
        'code' => $faker->uuid,
        'order' => $faker->biasedNumberBetween(0, 4),
        'active' => $faker->biasedNumberBetween(0, 1),
        'level'  => 1
    ];
});