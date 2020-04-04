<?php

namespace Tests;

use Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $useDatabase = false;

    public function setUp(): void
    {
        parent::setUp();

        if ($this->useDatabase) {
            Artisan::call('migrate');
        }
    }

    public function tearDown(): void
    {
        if ($this->useDatabase) {
            Artisan::call('migrate:reset');
        }

        parent::tearDown();
    }
}
