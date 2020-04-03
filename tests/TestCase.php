<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $useDatabase = false;

    public function setUp()
    {
        parent::setUp();

        if ($this->useDatabase) {
            Artisan::call('migrate');
        }
    }

    public function tearDown()
    {
        if ($this->useDatabase) {
            Artisan::call('migrate:reset');
        }

        parent::tearDown();
    }
}
