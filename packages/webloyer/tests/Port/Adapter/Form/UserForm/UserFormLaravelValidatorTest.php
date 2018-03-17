<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm;

use Illuminate\Support\MessageBag;
use Kodeine\Acl\Models\Eloquent\Role as EloquentRole;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\User as EloquentUser;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm\UserFormLaravelValidator;
use Tests\Helpers\EloquentFactory;
use TestCase;

class UserFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    private $userFormLaravelValidator;

    public function setUp()
    {
        parent::setUp();

        $this->userFormLaravelValidator = new UserFormLaravelValidator($this->app['validator']);
    }

    public function test_Should_PassToValidate_When_NameFieldIsValid()
    {
        $input = [
            'name' => 'User 1',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }

    public function test_Should_PassToValidate_When_EmailFieldIsValid()
    {
        $input = [
            'email' => 'user1@example.com',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }

    public function test_Should_FailToValidate_When_EmailFieldIsInvalid()
    {
        $input = [
            'email' => 'invalid',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_PassToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreValid()
    {
        $input = [
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }

    public function test_Should_FailToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreInvalid()
    {
        $input = [
            'password'              => '1234567',
            'password_confirmation' => '1234567',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreDifferent()
    {
        $input = [
            'password'              => '12345678',
            'password_confirmation' => '23456789',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_PassToValidate_When_RoleFieldIsValid()
    {
        EloquentFactory::create(
            EloquentRole::class,
            [
                'name'        => 'Role 1',
                'slug'        => 'role_1',
                'description' => '',
            ]
        );

        EloquentFactory::create(
            EloquentRole::class,
            [
                'name'        => 'Role 2',
                'slug'        => 'role_2',
                'description' => '',
            ]
        );

        $input = [
            'role' => [1, 2],
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }

    public function test_Should_FailToValidate_When_RoleFieldIsInvalid()
    {
        $input = [
            'role' => [1],
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_EmailFieldIsNotUniqueAndIdFieldIsNotSpecified()
    {
        EloquentFactory::create(
            EloquentUser::class,
            [
                'email' => 'user1@example.com',
            ]
        );

        $input = [
            'email' => 'user1@example.com',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_PassToValidate_When_EmailFieldIsNotUniqueAndIdFieldIsSpecified()
    {
        $user = EloquentFactory::create(
            EloquentUser::class,
            [
                'email' => 'user1@example.com',
            ]
        );

        $input = [
            'id'    => $user->id,
            'email' => 'user1@example.com',
        ];

        $actualResult = $this->userFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->userFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }
}
