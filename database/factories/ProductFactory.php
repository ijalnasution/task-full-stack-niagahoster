<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'price' => rand(1,100),
        'user_id' => rand(1,3)
    ];
});
