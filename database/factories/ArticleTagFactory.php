<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\ArticleTag::class, function (Faker $faker) {
    return [
        'tag_id' => random_int(1, 10),
        'article_id' => random_int(1, 100)
    ];
});
