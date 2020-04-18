<?php

declare(strict_types=1);

namespace Common;

use BadMethodCallException;
use InvalidArgumentException;
use ReflectionObject;

trait Enumerable
{
    /** @var mixed */
    private $scalar;

    /**
     * @param string            $name
     * @param array<int, mixed> $arguments
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        $class = get_called_class();
        $label = self::toConstantCase($name);
        $constant = constant($class . '::' . $label);
        return new $class($constant);
    }
    /**
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->scalar;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        throw new BadMethodCallException('All setters are forbbiden.');
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->scalar;
    }

    /**
     * @param string $string
     * @return string
     */
    private static function toConstantCase(string $string): string
    {
        return ltrim(strtoupper(preg_replace('/[A-Z]+/', '_\0', $string)), '_');
    }

    /**
     * @return void
     */
    private function __construct($value)
    {
        $reflection = new ReflectionObject($this);
        $constants = $reflection->getConstants();
        if (!in_array($value, $constants, true)) {
            throw new InvalidArgumentException(
                'Undefined value.' . PHP_EOL .
                'Value: ' . $value
            );
        }

        $this->scalar = $value;
    }
}
