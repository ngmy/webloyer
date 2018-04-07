<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserProvider;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Tests\Helpers\MockeryHelper;
use TestCase;

class UserProviderTest extends TestCase
{
    use MockeryHelper;

    private $hasher;

    private $userRepository;

    private $userProvider;

    public function setUp()
    {
        parent::setUp();

        $this->hasher = $this->mock(Hasher::class);
        $this->userRepository = $this->mock(UserRepositoryInterface::class);
        $this->userProvider = new UserProvider(
            $this->hasher,
            $this->userRepository
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function retrieveByCredentialsProvider()
    {
        return [
            [
                [],
                null,
            ],
            [
                ['email' => ''],
                true,
            ],
            [
                ['hoge' => ''],
                null,
            ],
        ];
    }

    public function test_Should_RetrieveById()
    {
        $userId = 1;
        $expectedResult = true;

        $this->userRepository
            ->shouldReceive('userOfId')
            ->with(\Hamcrest\Matchers::equalTo(new UserId($userId)))
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->userProvider->retrieveById($userId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_RetrieveByToken()
    {
        $identifier = '';
        $token = '';

        $this->userProvider->retrieveByToken($identifier, $token);

        $this->assertTrue(true);
    }

    public function test_Should_UpdateRememberToken()
    {
        $user = $this->mock(Authenticatable::class);
        $token = '';

        $this->userProvider->updateRememberToken($user, $token);

        $this->assertTrue(true);
    }

    /**
     * @dataProvider retrieveByCredentialsProvider
     */
    public function test_Should_retrieveByCredentials_When_($credentials, $expectedResult)
    {
        if (isset($credentials['email'])) {
            $this->userRepository
                ->shouldReceive('userOfEmail')
                ->with($credentials['email'])
                ->andReturn($expectedResult)
                ->once();
        }

        $actualResult = $this->userProvider->retrieveByCredentials($credentials);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_ValidateCredentials()
    {
        $user = $this->mock(Authenticatable::class);
        $credentials = [
            'password' => 'some password',
        ];
        $expectedResult = true;

        $user
            ->shouldReceive('password')
            ->withNoArgs()
            ->andReturn($credentials['password'])
            ->once();
        $this->hasher
            ->shouldReceive('check')
            ->with($credentials['password'], $credentials['password'])
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->userProvider->validateCredentials($user, $credentials);

        $this->assertEquals($expectedResult, $actualResult);
    }
}
