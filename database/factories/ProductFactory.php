<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'sku' => $faker->safeColorName,
        'name' => $faker->company,
        'slug' => $faker->slug(),
        'category_id' => $faker->numberBetween(3, 6),
        'price' => $faker->numberBetween(10000, 1000000),
        'weight' => $faker->numberBetween(1000, 10000),
        'description' => $faker->paragraphs(),
        'status' => '1'
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'sku' => $faker->safeColorName,
        'name' => $faker->company,
        'slug' => $faker->slug(),
        'category_id' => $faker->numberBetween(3, 6),
        'price' => $faker->numberBetween(10000, 1000000),
        'weight' => $faker->numberBetween(1000, 10000),
        'description' => $faker->paragraphs(),
        'status' => '1'
    ];
});
