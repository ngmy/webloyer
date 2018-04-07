<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\HasRole;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Tests\Helpers\MockeryHelper;
use TestCase;

class HasRoleTest extends TestCase
{
    use MockeryHelper;

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    /**
     * @dataProvider isProvider
     */
    public function test_Should_VerifyUserIsSpecifiedRole_When_($slug, $operator)
    {
        $user = $this->createUserUsingHasRole();
        $expectedResult = true;

        $userRepository = $this->mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('is')
            ->with($user, $slug, $operator)
            ->andReturn($expectedResult)
            ->once();
        $this->app->instance(UserRepositoryInterface::class, $userRepository);

        $actualResult = $user->is($slug, $operator);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function isProvider()
    {
        return [
            [new RoleSlug('administrator'), null],
            [new RoleSlug('administrator'), 'OR'],
        ];
    }

    public function createUserUsingHasRole()
    {
        return new class() extends User {
            use HasRole;

            public function __construct()
            {
            }
        };
    }
}
