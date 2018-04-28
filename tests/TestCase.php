<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost';

    protected $useDatabase = false;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

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
