<?php

use Faker\Generator as Faker;
use Modules\Administration\Entities\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName('male'),
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt($faker->password),
        'status' => '1',
        'remember_token' => str_random(60)
    ];
});
