<?php

use Faker\Generator as Faker;
use Mitosis\Channel\Models\Channel;

$factory->define(Channel::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
