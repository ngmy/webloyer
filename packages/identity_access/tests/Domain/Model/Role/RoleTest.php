<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\Role;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use TestCase;

class RoleTest extends TestCase
{
    public function test_Should_GetRoleId()
    {
        $expectedResult = new RoleId(1);

        $role = $this->createRole([
            'roleId' => $expectedResult->id(),
        ]);

        $actualResult = $role->roleId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetName()
    {
        $expectedResult = 'some name';

        $role = $this->createRole([
            'name' => $expectedResult,
        ]);

        $actualResult = $role->name();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSlug()
    {
        $expectedResult = new RoleSlug('developer');

        $role = $this->createRole([
            'slug' => $expectedResult->value(),
        ]);

        $actualResult = $role->slug();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDescription()
    {
        $expectedResult = 'some description';

        $role = $this->createRole([
            'description' => $expectedResult,
        ]);

        $actualResult = $role->description();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRole(),
            $this->createRole(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRole(),
            $this->createRole([
                'roleId' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createRole(array $params = [])
    {
        $roleId = 1;
        $name = '';
        $slug = 'administrator';
        $description = '';

        extract($params);

        return new Role(
            new RoleId($roleId),
            $name,
            new RoleSlug($slug),
            $description
        );
    }
}
