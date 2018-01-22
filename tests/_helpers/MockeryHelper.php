<?php

namespace Tests\Helpers;

use InvalidArgumentException;
use Mockery;

trait MockeryHelper
{
    protected function mock()
    {
        $args = func_get_args();

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

    protected function partialMock()
    {
        $mock = call_user_func_array([$this, 'mock'], func_get_args());

        return $mock->makePartial();
    }

    protected function closeMock()
    {
        Mockery::close();
    }
}
