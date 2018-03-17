<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\User;

use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use TestCase;

class UserIdTest extends TestCase
{
    public function test_Should_GetId()
    {
        $expectedResult = 1;

        $recipe = $this->createUserId([
            'id' => $expectedResult,
        ]);

        $actualResult = $recipe->id();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createUserId(),
            $this->createUserId([
                'id' => 1,
            ]),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createUserId(),
            $this->createUserId([
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

    private function createUserId(array $params = [])
    {
        $id = 1;

        extract($params);

        return new UserId($id);
    }
}
