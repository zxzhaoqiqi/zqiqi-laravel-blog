<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\PageVariables::class, function (Faker $faker) {
    return [
        'meta_title' => $faker->text,
        'meta_keyword' => $faker->text,
        'meta_description' => $faker->text,
        'type' => random_int(0, 1)
    ];
});
