<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\Authenticatable;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Tests\Helpers\MockeryHelper;
use TestCase;

class AuthenticatableTest extends TestCase
{
    use MockeryHelper;

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_GetAuthIdentifierName()
    {
        $user = $this->createUserUsingAuthenticatable();
        $expectedResult = 'id';

        $userRepository = $this->mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('identityName')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();
        $this->app->instance(UserRepositoryInterface::class, $userRepository);

        $actualResult = $user->getAuthIdentifierName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAuthIdentifier()
    {
        $expectedResult = 1;
        $user = $this->createUserUsingAuthenticatable([
            'userId' => $expectedResult,
        ]);

        $actualResult = $user->getAuthIdentifier();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAuthPassword()
    {
        $expectedResult = 'some password';
        $user = $this->createUserUsingAuthenticatable([
            'password' => $expectedResult,
        ]);

        $actualResult = $user->getAuthPassword();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRememberToken()
    {
        $expectedResult = 'some remember token';
        $user = $this->createUserUsingAuthenticatable([
            'rememberToken' => $expectedResult,
        ]);

        $userRepository = $this->mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('rememberTokenName')
            ->withNoArgs()
            ->andReturn('remember_token')
            ->once();
        $this->app->instance(UserRepositoryInterface::class, $userRepository);

        $actualResult = $user->getRememberToken();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetRememberToken()
    {
        $rememberToken = 'some remember token';
        $expectedResult = $this->createUserUsingAuthenticatable([
            'rememberToken' => $rememberToken,
        ]);
        $actualResult = $this->createUserUsingAuthenticatable();

        $userRepository = $this->mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('rememberTokenName')
            ->withNoArgs()
            ->andReturn('remember_token')
            ->once();
        $this->app->instance(UserRepositoryInterface::class, $userRepository);

        $actualResult->setRememberToken($rememberToken);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRememberTokenName()
    {
        $user = $this->createUserUsingAuthenticatable();
        $expectedResult = 'remember_token';

        $userRepository = $this->mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('rememberTokenName')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();
        $this->app->instance(UserRepositoryInterface::class, $userRepository);

        $actualResult = $user->getRememberTokenName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function createUserUsingAuthenticatable(array $params = [])
    {
        $userId = 1;
        $password = '';
        $rememberToken = '';

        extract($params);

        $user = new class($userId, $password, $rememberToken) extends User {
            use Authenticatable;

            private $userId;

            private $password;

            private $remember_token;

            public function __construct($userId, $password, $rememberToken)
            {
                $this->userId = new UserId($userId);
                $this->password = $password;
                $this->remember_token = $rememberToken;
            }

            public function userId()
            {
                return $this->userId;
            }

            public function password()
            {
                return $this->password;
            }
        };

        return $user;
    }
}
