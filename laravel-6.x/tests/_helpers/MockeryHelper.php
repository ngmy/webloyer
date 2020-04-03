<?php

namespace Tests\Helpers;

use Mockery as m;

trait MockeryHelper
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    protected function mock($class)
    {
        $mock = m::mock($class);

        $this->app->instance($class, $mock);

        return $mock;
    }

    protected function mockPartial($class)
    {
        $mock = m::mock($class)->makePartial();

        $this->app->instance($class, $mock);

        return $mock;
    }
}
