<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\HasPermission;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Tests\Helpers\MockeryHelper;
use TestCase;

class HasPermissionTest extends TestCase
{
    use MockeryHelper;

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    /**
     * @dataProvider canProvider
     */
    public function test_Should_VerifyUserIsSpecifiedPermission_When_($permission, $operator)
    {
        $user = $this->createUserUsingHasPermission();
        $expectedResult = true;

        $userRepository = $this->mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('can')
            ->with($user, $permission, $operator)
            ->andReturn($expectedResult)
            ->once();
        $this->app->instance(UserRepositoryInterface::class, $userRepository);

        $actualResult = $user->can($permission, $operator);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function canProvider()
    {
        return [
            ['some permission', null],
            ['some permission', 'OR'],
        ];
    }

    public function createUserUsingHasPermission()
    {
        return new class() extends User {
            use HasPermission;

            public function __construct()
            {
            }
        };
    }
}
