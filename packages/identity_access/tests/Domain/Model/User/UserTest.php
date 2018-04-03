<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Carbon\Carbon;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use TestCase;

class UserTest extends TestCase
{
    public function test_Should_GetUserId()
    {
        $expectedResult = new UserId(1);

        $user = $this->createUser([
            'userId' => $expectedResult->id(),
        ]);

        $actualResult = $user->userId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetName()
    {
        $expectedResult = 'some name';

        $user = $this->createUser([
            'name' => $expectedResult,
        ]);

        $actualResult = $user->name();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetEmail()
    {
        $expectedResult = 'some email';

        $user = $this->createUser([
            'email' => $expectedResult,
        ]);

        $actualResult = $user->email();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetPassword()
    {
        $expectedResult = 'some password';

        $user = $this->createUser([
            'password' => $expectedResult,
        ]);

        $actualResult = $user->password();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetApiToken()
    {
        $expectedResult = 'some api token';

        $user = $this->createUser([
            'apiToken' => $expectedResult,
        ]);

        $actualResult = $user->apiToken();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRoleIds()
    {
        $expectedResult = [
            new RoleId(1),
            new RoleId(2),
        ];

        $user = $this->createUser([
            'roleIds' => array_map(function ($roleId) {
                return $roleId->id();
            }, $expectedResult),
        ]);

        $actualResult = $user->roleIds();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetCreatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $user = $this->createUser([
            'createdAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $user->createdAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUpdatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $user = $this->createUser([
            'updatedAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $user->updatedAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_HasRoleIdReturnTrue_When_UserHasRole()
    {
        $roleIds = [
            new RoleId(1),
        ];

        $user = $this->createUser([
            'roleIds' => array_map(function ($roleId) {
                return $roleId->id();
            }, $roleIds),
        ]);

        $actualResult = $user->hasRoleId($roleIds[0]);

        $this->assertTrue($actualResult);
    }

    public function test_Should_HasRoleIdReturnTrue_When_UserHasNotRole()
    {
        $user = $this->createUser();

        $actualResult = $user->hasRoleId(new RoleId(1));

        $this->assertFalse($actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createUser(),
            $this->createUser(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createUser(),
            $this->createUser([
                'userId' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createUser(array $params = [])
    {
        $userId = 1;
        $name = '';
        $email = '';
        $password = '';
        $apiToken = '';
        $roleIds = [];
        $createdAt = '';
        $updatedAt = '';

        extract($params);

        return new User(
            new UserId($userId),
            $name,
            $email,
            $password,
            $apiToken,
            array_map(function ($roleId) {
                return new RoleId($roleId);
            }, $roleIds),
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }
}
