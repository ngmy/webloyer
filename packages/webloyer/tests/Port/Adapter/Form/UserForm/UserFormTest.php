<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm\UserForm;
use Tests\Helpers\MockeryHelper;
use TestCase;

class UserFormTest extends TestCase
{
    use MockeryHelper;

    private $validator;

    private $userService;

    private $userForm;

    private $inputForSave = [
        'name'     => null,
        'email'    => null,
        'password' => null,
        'role'     => [],
    ];

    private $inputForUpdate = [
        'id'                  => null,
        'name'                => null,
        'email'               => null,
        'concurrency_version' => null,
    ];

    private $inputForUpdatePassword = [
        'id'                  => null,
        'password'            => null,
        'concurrency_version' => null,
    ];

    private $inputForUpdateRole = [
        'id'                  => null,
        'role'                => [],
        'concurrency_version' => null,
    ];

    private $inputForRegerateApiToken = [
        'id'                  => null,
        'concurrency_version' => null,
    ];

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->mock(ValidableInterface::class);
        $this->userService = $this->mock(UserService::class);
        $this->userForm = new UserForm(
            $this->validator,
            $this->userService
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_SucceedToSave_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->userService
            ->shouldReceive('saveUser');

        $actualResult = $this->userForm->save($this->inputForSave);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->userForm->save($this->inputForSave);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $user = $this->mock(User::class);
        $user->shouldReceive('password');
        $user->shouldReceive('apiToken');
        $roleIds = [new RoleId(1)];
        $user->shouldReceive('roleIds')->andReturn($roleIds);
        $this->userService
            ->shouldReceive('getUserById')
            ->andReturn($user);
        $this->userService
            ->shouldReceive('saveUser');

        $actualResult = $this->userForm->update($this->inputForUpdate);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->userForm->update($this->inputForUpdate);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdatePassword_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $user = $this->mock(User::class);
        $user->shouldReceive('name');
        $user->shouldReceive('email');
        $user->shouldReceive('apiToken');
        $roleIds = [new RoleId(1)];
        $user->shouldReceive('roleIds')->andReturn($roleIds);
        $this->userService
            ->shouldReceive('getUserById')
            ->andReturn($user);
        $this->userService
            ->shouldReceive('saveUser');

        $actualResult = $this->userForm->updatePassword($this->inputForUpdatePassword);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToUpdatePassword_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->userForm->updatePassword($this->inputForUpdatePassword);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdateRole_When_ValidationPassesAndRoleIsSet()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $user = $this->mock(User::class);
        $user->shouldReceive('name');
        $user->shouldReceive('email');
        $user->shouldReceive('password');
        $user->shouldReceive('apiToken');
        $this->userService
            ->shouldReceive('getUserById')
            ->andReturn($user);
        $this->userService
            ->shouldReceive('saveUser');

        $actualResult = $this->userForm->updateRole($this->inputForUpdateRole);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_SucceedToUpdateRole_When_ValidationPassesAndRoleIsNotSet()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $user = $this->mock(User::class);
        $user->shouldReceive('name');
        $user->shouldReceive('email');
        $user->shouldReceive('password');
        $user->shouldReceive('apiToken');
        $this->userService
            ->shouldReceive('getUserById')
            ->andReturn($user);
        $this->userService
            ->shouldReceive('saveUser');

        unset($this->inputForUpdateRole['role']);

        $actualResult = $this->userForm->updateRole($this->inputForUpdateRole);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToUpdateRole_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->userForm->updateRole($this->inputForUpdateRole);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_SucceedToRegenerateApiToken()
    {
        $user = $this->mock(User::class);
        $user->shouldReceive('name');
        $user->shouldReceive('email');
        $user->shouldReceive('password');
        $roleIds = [new RoleId(1)];
        $user->shouldReceive('roleIds')->andReturn($roleIds);
        $this->userService
            ->shouldReceive('getUserById')
            ->andReturn($user);
        $this->userService
            ->shouldReceive('saveUser');

        $actualResult = $this->userForm->regenerateApiToken($this->inputForUpdatePassword);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_GetValidationErrors()
    {
        $expectedResult = new MessageBag();

        $this->validator
            ->shouldReceive('errors')
            ->andReturn($expectedResult);

        $actualResult = $this->userForm->errors();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
