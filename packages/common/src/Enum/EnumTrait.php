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

    final public static function isValidName($name)
    {
        return array_key_exists($name, self::ENUM);
    }

    final public static function __callStatic($name, array $args)
    {
        if (!self::isValidName($name)) {
            throw new BadMethodCallException("Invalid enum name (name='{$name}')");
        }

        return new self(self::ENUM[$name]);
    }

    final public function __toString()
    {
        return (string) $this->scalar;
    }

    final public function value()
    {
        return $this->scalar;
    }

    final public function __set($name, $value)
    {
        throw new BadMethodCallException('All setter is forbidden');
    }
}
