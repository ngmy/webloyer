<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use InvalidArgumentException;
use Ngmy\Webloyer\Common\QueryObject\AbstractCriteria;
use Ngmy\Webloyer\Common\QueryObject\Limit;
use Ngmy\Webloyer\Common\QueryObject\Order;
use Ngmy\Webloyer\Common\QueryObject\Pagination;
use Ngmy\Webloyer\Common\QueryObject\QueryObject;
use Tests\Helpers\MockeryHelper;
use TestCase;

class QueryObjectTest extends TestCase
{
    use MockeryHelper;

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function setLimitProvider()
    {
        return [
            [$this->mock(Limit::class), null],
            [$this->mock(Limit::class), $this->mock(Pagination::class)],
        ];
    }

    public function setPaginationProvider()
    {
        return [
            [$this->mock(Pagination::class), null],
            [$this->mock(Pagination::class), $this->mock(Limit::class)],
        ];
    }

    public function test_Should_GetCriteria()
    {
        $expectedResult = $this->mock(AbstractCriteria::class);

        $queryObject = $this->createQueryObject(['criteria' => $expectedResult]);

        $actualResult = $queryObject->criteria();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetOrders()
    {
        $expectedResult = [
            $this->mock(Order::class),
        ];

        $queryObject = $this->createQueryObject(['orders' => $expectedResult]);

        $actualResult = $queryObject->orders();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetLimit()
    {
        $expectedResult = $this->mock(Limit::class);

        $queryObject = $this->createQueryObject(['limit' => $expectedResult]);

        $actualResult = $queryObject->limit();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetPagination()
    {
        $expectedResult = $this->mock(Pagination::class);

        $queryObject = $this->createQueryObject(['pagination' => $expectedResult]);

        $actualResult = $queryObject->pagination();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetCriteria()
    {
        $queryObject = $this->createQueryObject();
        $criteria = $this->mock(AbstractCriteria::class);

        $actualResult = $queryObject->setCriteria($criteria);

        $this->assertEquals($queryObject, $actualResult);
    }

    public function test_Should_AddOrder()
    {
        $queryObject = $this->createQueryObject();
        $order = $this->mock(Order::class);

        $actualResult = $queryObject->addOrder($order);

        $this->assertEquals($queryObject, $actualResult);
    }

    /**
     * @dataProvider setLimitProvider
     */
    public function test_Should_SetLimit_When_($limit, $pagination)
    {
        $queryObject = $this->createQueryObject();

        if (is_null($pagination)) {
            $actualResult = $queryObject->setLimit($limit);

            $this->assertEquals($queryObject, $actualResult);
        } else {
            try {
                $queryObject->setPagination($pagination);
                $queryObject->setLimit($limit);
            } catch (InvalidArgumentException $e) {
                $this->assertTrue(true);
                return;
            }
            $this->fail();
        }
    }

    /**
     * @dataProvider setPaginationProvider
     */
    public function test_Should_SetPagination_When_($pagination, $limit)
    {
        $queryObject = $this->createQueryObject();

        if (is_null($limit)) {
            $actualResult = $queryObject->setPagination($pagination);

            $this->assertEquals($queryObject, $actualResult);
        } else {
            try {
                $queryObject->setLimit($limit);
                $queryObject->setPagination($pagination);
            } catch (InvalidArgumentException $e) {
                $this->assertTrue(true);
                return;
            }
            $this->fail();
        }
    }

    private function createQueryObject(array $params = [])
    {
        $criteria = null;
        $orders = [];
        $limit = null;;
        $pagination = null;

        extract($params);

        $queryObject = new QueryObject();

        if (!is_null($criteria)) {
            $queryObject->setCriteria($criteria);
        }
        if (!empty($orders)) {
            array_walk($orders, [$queryObject, 'addOrder']);
        }
        if (!is_null($limit)) {
            $queryObject->setLimit($limit);
        }
        if (!is_null($pagination)) {
            $queryObject->setPagination($pagination);
        }

        return $queryObject;
    }
}
