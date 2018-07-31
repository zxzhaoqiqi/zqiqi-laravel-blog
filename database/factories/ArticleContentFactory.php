<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\ArticleContent::class, function (Faker $faker) {
    return [
        'article_id' => random_int(1, 100),
        'content' => $faker->realText()
    ];
});
