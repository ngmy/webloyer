<?php

namespace Ngmy\Webloyer\Common\Enum;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;
use Ngmy\Webloyer\Common\Enum\EnumTrait;
use TestCase;

class EnumTraitTest extends TestCase
{
    public function constructProvider()
    {
        return [
            [1, null],
            [2, null],
            [3, null],
            [4, InvalidArgumentException::class],
        ];
    }

    public function factoryMethodProvider()
    {
        return [
            ['key1', 1, null],
            ['key2', 2, null],
            ['key3', 3, null],
            ['key4', 4, BadMethodCallException::class],
        ];
    }

    public function toStringProvider()
    {
        return [
            [1, '1'],
            [2, '2'],
            [3, '3'],
        ];
    }

    public function valueProvider()
    {
        return [
            [1, 1],
            [2, 2],
            [3, 3],
        ];
    }

    /**
     * @dataProvider constructProvider
     */
    public function test_Should_CreateInstanceByConstructor_When_($value, $expectedException)
    {
        if (is_null($expectedException)) {
            try {
                $this->createAnonymousUsingEnumTrait(['value' => $value]);
            } catch (Exception $e) {
                $this->fail();
            }
            $this->assertTrue(true);
        } else {
            try {
                $this->createAnonymousUsingEnumTrait(['value' => $value]);
            } catch (Exception $e) {
                if ($e instanceof $expectedException) {
                    $this->assertTrue(true);
                    return;
                }
            }
            $this->fail();
        }
    }

    /**
     * @dataProvider factoryMethodProvider
     */
    public function test_Should_CreateInstanceByFactoryMethod_When_($key, $value, $expectedException)
    {
        if (is_null($expectedException)) {
            try {
                $expectedException = $this->createAnonymousUsingEnumTrait(['value' => $value]);
                $class = $this->createAnonymousUsingEnumTrait();
                $actualResult = $class::$key();
            } catch (Exception $e) {
                $this->fail();
            }
            $this->assertEquals($expectedException, $actualResult);
        } else {
            try {
                $class = $this->createAnonymousUsingEnumTrait();
                $class::$key();
            } catch (Exception $e) {
                if ($e instanceof $expectedException) {
                    $this->assertTrue(true);
                    return;
                }
            }
            $this->fail();
        }
    }

    /**
     * @dataProvider toStringProvider
     */
    public function test_Should_GetStringRepresentation_When_($value, $expectedResult)
    {
        $class = $this->createAnonymousUsingEnumTrait(['value' => $value]);

        $actualResult = $class->__toString();

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @dataProvider valueProvider
     */
    public function test_Should_GetOriginalValue_When_($value, $expectedResult)
    {
        $class = $this->createAnonymousUsingEnumTrait(['value' => $value]);

        $actualResult = $class->value();

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function test_Should_AllSetterIsForbbiden()
    {
        $class = $this->createAnonymousUsingEnumTrait();

        $class->key1 = 'some value';
    }

    public function createAnonymousUsingEnumTrait(array $params = [])
    {
        $value = 1;

        extract($params);

        return new class($value) {
            use EnumTrait;

            const ENUM = [
                'key1' => 1,
                'key2' => 2,
                'key3' => 3,
            ];
        };
    }
}
