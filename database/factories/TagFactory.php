<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'path' => $faker->word,
        'thumb' => $faker->imageUrl(60, 10),
        'description' => $faker->text
    ];
});
