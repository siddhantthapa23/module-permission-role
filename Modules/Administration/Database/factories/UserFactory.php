<?php

use Faker\Generator as Faker;
use Modules\Administration\Entities\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName('male'),
        'middle_name' => $faker->word,
        'last_name' => $faker->lastName,
        'gender' => $faker->numberBetween($min = 0, $max = 2),
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'avatar' => $faker->image($dir = '/tmp', $width = 640, $height = 480),
        'email' => $faker->unique()->safeEmail,
        'password' => $faker->password,
        'status' => 1,
        'remember_token' => $faker->md5,
        'created_by' => $faker->randomDigit,
        'updated_by' => $faker->randomDigit
    ];
});
