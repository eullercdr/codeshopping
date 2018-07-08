<?php

use Faker\Generator as Faker;

$factory->define(CodeShopping\Models\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'description' => $faker->sentence,
        'price' => $faker->randomFloat(3, 0, 1000),
        'stock' => $faker->randomDigit(1000)
    ];
});
