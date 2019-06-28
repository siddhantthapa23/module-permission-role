<?php

use Faker\Generator as Faker;
use Modules\Administration\Entities\Role;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'guard_name' => 'web'
    ];
});
