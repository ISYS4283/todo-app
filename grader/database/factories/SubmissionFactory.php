<?php

use Faker\Generator as Faker;

$factory->define(App\Submission::class, function (Faker $faker) {
    return [
        'host' => $faker->unique()->localIpv4,
        'user_token' => 'eyJ1c2VybmFtZSI6ImplZmYiLCJwYXNzd29yZCI6IklTWVM0MjgzIGlzIHRoZSBiZXN0ISIsImRhdGFiYXNlIjoidG9kb2FwcCIsImhvc3RuYW1lIjoibG9jYWxob3N0In0=',
        'user_id' => function(){
            return factory(App\User::class)->create()->id;
        },
    ];
});
