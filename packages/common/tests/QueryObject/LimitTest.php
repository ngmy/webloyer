<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use TestCase;

class LimitTest extends TestCase
{
    public function test_Should_GetOffset()
    {
        $expectedResult = 1;

        $limit = $this->createLimit(['offset' => $expectedResult]);

        $actualResult = $limit->offset();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetLimit()
    {
        $expectedResult = 20;

        $limit = $this->createLimit(['limit' => $expectedResult]);

        $actualResult = $limit->limit();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createLimit(array $params = [])
    {
        $offset = 0;
        $limit = 10;

        extract($params);

        return new Limit(
            $offset,
            $limit
        );
    }
}
