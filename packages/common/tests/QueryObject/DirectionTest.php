<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use TestCase;

class DirectionTest extends TestCase
{
    public function constructorProvider()
    {
        return [
            ['asc', new Direction('asc')],
            ['desc', new Direction('desc')],
        ];
    }

    /**
     * @dataProvider constructorProvider
     */
    public function test_Should_CreateInstance_When_($value, $expectedResult)
    {
        $actualResult = $this->createDirection(['value' => $value]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDirection(array $params = [])
    {
        $value = 'asc';

        extract($params);

        return new Direction($value);
    }
}
