<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use TestCase;

class PaginationTest extends TestCase
{
    public function test_Should_GetPage()
    {
        $expectedResult = 2;

        $limit = $this->createPagination(['page' => $expectedResult]);

        $actualResult = $limit->page();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetLimit()
    {
        $expectedResult = 20;

        $limit = $this->createPagination(['limit' => $expectedResult]);

        $actualResult = $limit->limit();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createPagination(array $params = [])
    {
        $page = 1;
        $limit = 10;

        extract($params);

        return new Pagination(
            $page,
            $limit
        );
    }
}
