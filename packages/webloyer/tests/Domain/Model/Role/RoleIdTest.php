<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Role;

use Ngmy\Webloyer\Webloyer\Domain\Model\Role\RoleId;
use TestCase;

class RoleIdTest extends TestCase
{
    public function test_Should_GetId()
    {
        $expectedResult = 1;

        $roleId = $this->createRoleId([
            'id' => $expectedResult,
        ]);

        $actualResult = $roleId->id();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRoleId(),
            $this->createRoleId(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRoleId(),
            $this->createRoleId([
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

    private function createRoleId(array $params = [])
    {
        $id = 1;

        extract($params);

        return new RoleId($id);
    }
}
