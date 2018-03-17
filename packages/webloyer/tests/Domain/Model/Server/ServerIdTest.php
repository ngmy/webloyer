<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Server;

use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use TestCase;

class ServerIdTest extends TestCase
{
    public function test_Should_GetId()
    {
        $expectedResult = 1;

        $recipe = $this->createServerId([
            'id' => $expectedResult,
        ]);

        $actualResult = $recipe->id();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createServerId(),
            $this->createServerId([
                'id' => 1,
            ]),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createServerId(),
            $this->createServerId([
                'id' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createServerId(array $params = [])
    {
        $id = 1;

        extract($params);

        return new ServerId($id);
    }
}
