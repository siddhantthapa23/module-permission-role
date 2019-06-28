<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected $faker;

    /**
     * Setup the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->app->make(\Modules\Administration\Registrars\PermissionRegistrar::class)->registerPermissions();

        $this->faker = Faker::create();
    }
}
