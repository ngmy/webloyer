<?php

namespace Ngmy\Webloyer\IdentityAccess\Application\User;

use Mockery;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use TestCase;
use Tests\Helpers\MockeryHelper;

class UserServiceTest extends TestCase
{
    use MockeryHelper;

    private $userRepository;

    private $userService;

    private $inputForGetUsersByPage = [
        'page'    => 1,
        'perPage' => 10,
    ];

    private $inputForSaveUser = [
        'userId'             => 1,
        'name'               => '',
        'email'              => '',
        'password'           => '',
        'apiToken'           => '',
        'roleIds'            => [1],
        'concurrencyVersion' => '',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = $this->mock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function test_Should_GetAllUsers()
    {
        $expectedResult = true;

        $this->userRepository
            ->shouldReceive('allUsers')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->userService->getAllUsers();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUsersByPage_When_PageAndPerPageIsNotSpecified()
    {
        $this->checkGetUsersByPage(null, null, 1, 10);
    }

    public function test_Should_GetUsersByPage_When_PageAndPerPageIsSpecified()
    {
        $this->checkGetUsersByPage(2, 20, 2, 20);
    }

    public function test_Should_GetUserById()
    {
        $expectedResult = true;
        $userId = new UserId(1);

        $this->userRepository
            ->shouldReceive('userOfId')
            ->with(\Hamcrest\Matchers::equalTo($userId))
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->userService->getUserById($userId->id());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SaveUser_When_UserIdIsNull()
    {
        $this->checkSaveUser(true, false);
    }

    public function test_Should_SaveUser_When_UserIdIsNotNullAndUserExists()
    {
        $this->checkSaveUser(false, true);
    }

    public function test_Should_SaveUser_When_UserIdIsNotNullAndUserNotExists()
    {
        $this->checkSaveUser(false, false);
    }

    public function test_Should_RemoveUser()
    {
        $userId = new UserId(1);
        $user = $this->mock(User::class);
        $this->userRepository
            ->shouldReceive('userOfId')
            ->with(\Hamcrest\Matchers::equalTo($userId))
            ->andReturn($user)
            ->once();
        $this->userRepository
            ->shouldReceive('remove')
            ->with($user)
            ->once();

        $this->userService->removeUser($userId->id());

        $this->assertTrue(true);
    }

    private function checkGetUsersByPage($inputPage, $inputPerPage, $expectedPage, $expectedPerPage)
    {
        $this->inputForGetUsersByPage['page'] = $inputPage;
        $this->inputForGetUsersByPage['perPage'] = $inputPerPage;

        $expectedResult = true;
        $this->userRepository
            ->shouldReceive('usersOfPage')
            ->with($expectedPage, $expectedPerPage)
            ->once()
            ->andReturn($expectedResult);

        extract($this->inputForGetUsersByPage);

        if (isset($page) && isset($perPage)) {
            $actualResult = $this->userService->getUsersByPage($page, $perPage);
        } elseif (isset($page)) {
            $actualResult = $this->userService->getUsersByPage($page);
        } else {
            $actualResult = $this->userService->getUsersByPage();
        }

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function checkSaveUser($isNullInputUserId, $existsUser)
    {
        if ($isNullInputUserId) {
            $this->inputForSaveUser['userId'] = null;
        } else {
            $this->inputForSaveUser['userId'] = 1;
            if ($existsUser) {
                $user = $this->mock(User::class);
                $user
                    ->shouldReceive('failWhenConcurrencyViolation')
                    ->with($this->inputForSaveUser['concurrencyVersion'])
                    ->once();
            } else {
                $user = null;
            }
            $this->userRepository
                ->shouldReceive('userOfId')
                ->with(Mockery::on(function ($arg) {
                    return $arg == new UserId($this->inputForSaveUser['userId']);
                }))
                ->once()
                ->andReturn($user);
        }

        $this->userRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveUser);
                $user = new User(
                    new UserId($userId),
                    $name,
                    $email,
                    $password,
                    $apiToken,
                    array_map(function ($roleId) {
                        return new RoleId($roleId);
                    }, $roleIds),
                    null,
                    null
                );
                return $arg == $user;
            }))
            ->once();

        extract($this->inputForSaveUser);

        $this->userService->saveUser(
            $userId,
            $name,
            $email,
            $password,
            $apiToken,
            $roleIds,
            $concurrencyVersion
        );

        $this->assertTrue(true);
    }
}
