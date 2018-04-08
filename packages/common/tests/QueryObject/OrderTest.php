<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use Ngmy\Webloyer\Common\QueryObject\Direction;
use TestCase;

class OrderTest extends TestCase
{
    public function test_Should_GetColumn()
    {
        $expectedResult = 'some_column';

        $limit = $this->createOrder(['column' => $expectedResult]);

        $actualResult = $limit->column();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDirection()
    {
        $expectedResult = 'desc';

        $limit = $this->createOrder(['direction' => $expectedResult]);

        $actualResult = $limit->direction();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createOrder(array $params = [])
    {
        $column = '';
        $direction = 'asc';

        extract($params);

        return new Order(
            $column,
            new Direction($direction)
        );
    }
}
