<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->realText(30),
        'thumb' => $faker->imageUrl(50, 10),
        'is_show' => random_int(0, 1),
        'path' => $faker->word,
        'page_veriable_id' => random_int(1, 100),
    ];
});
