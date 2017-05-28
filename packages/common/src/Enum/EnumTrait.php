<?php

namespace Ngmy\Webloyer\Common\Enum;

use BadMethodCallException;
use InvalidArgumentException;

trait EnumTrait
{
    private $scalar;

    final public function __construct($value)
    {
        if (!self::isValidValue($value)) {
            throw new InvalidArgumentException("Invalid enum value (value='{$value}')");
        }

        $this->scalar = $value;
    }

    final public static function isValidValue($value)
    {
        return in_array($value, self::ENUM, true);
    }

    final public static function isValidKey($key)
    {
        return array_key_exists($key, self::ENUM);
    }

    final public static function __callStatic($method, array $args)
    {
        if (!self::isValidKey($method)) {
            throw new BadMethodCallException("Undefined static method (method='{$method}')");
        }

        return new self(self::ENUM[$method]);
    }

    final public function __toString()
    {
        return (string) $this->scalar;
    }

    final public function value()
    {
        return $this->scalar;
    }

    final public function __set($key, $value)
    {
        throw new BadMethodCallException('All setter is forbbiden');
    }
}
