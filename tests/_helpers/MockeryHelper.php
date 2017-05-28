<?php

namespace Tests\Helpers;

use InvalidArgumentException;
use Mockery;

trait MockeryHelper
{
    protected function mock()
    {
        $args = func_get_args();

        $mock = $this->createMock($args);

        return $mock;
    }

    protected function partialMock()
    {
        $args = func_get_args();

        $mock = $this->createMock($args);

        return $mock->makePartial();
    }

    protected function closeMock()
    {
        Mockery::close();
    }

    private function createMock(array $args)
    {
        $numArgs = count($args);

        if ($numArgs == 1) {
            $class = $args[0];
            $mock = Mockery::mock($class);
        } elseif ($numArgs == 2) {
            $class = $args[0];
            $constructorArgs = $args[1];
            $mock = Mockery::mock($class, $constructorArgs);
        } elseif ($numArgs == 3) {
            $class = $args[0];
            $interface = $args[1];
            $constructorArgs = $args[2];
            $mock = Mockery::mock($class, $interface, $constructorArgs);
        } else {
            throw new InvalidArgumentException('Invalid number of arguments.');
        }

        return $mock;
    }
}
