<?php

use Faker\Generator as Faker;
use Modules\Administration\Entities\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'guard_name' => 'web',
        'group_name' => $faker->word
    ];
});
