<?php

use Faker\Generator as Faker;

$factory->define(App\ToDo::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'completed' => $faker->boolean,
    ];
});
