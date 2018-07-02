<?php

use Faker\Generator as Faker;

$factory->define(App\Page::class, function (Faker $faker) {
    $word = $faker->word;
    return [
        'name' => $word,
        'title' => $word . ', ' . $faker->text(15),
        'content' => "\t<p>".implode("</p>\n\r\t<p>", $faker->paragraphs($nb = 3, $asText = false))."</p>\n\r",
        'slug' => $word,
    ];
});
