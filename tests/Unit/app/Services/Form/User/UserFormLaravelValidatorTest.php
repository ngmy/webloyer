<?php

namespace Tests\Unit\app\Services\Form\User;

use App\Models\User;
use App\Services\Form\User\UserFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Kodeine\Acl\Models\Eloquent\Role;
use Tests\TestCase;

class UserFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    public function testShouldPassToValidateWhenNameFieldIsValid()
    {
        $input = [
            'name' => 'User 1',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }

    public function testShouldPassToValidateWhenEmailFieldIsValid()
    {
        $input = [
            'email' => 'user1@example.com',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }

    public function testShouldFailToValidateWhenEmailFieldIsInvalid()
    {
        $input = [
            'email' => 'invalid',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldPassToValidateWhenPasswordFieldAndPasswordConfirmationFieldAreValid()
    {
        $input = [
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }

    public function testShouldFailToValidateWhenPasswordFieldAndPasswordConfirmationFieldAreInvalid()
    {
        $input = [
            'password'              => '1234567',
            'password_confirmation' => '1234567',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenPasswordFieldAndPasswordConfirmationFieldAreDifferent()
    {
        $input = [
            'password'              => '12345678',
            'password_confirmation' => '23456789',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldPassToValidateWhenRoleFieldIsValid()
    {
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $input = [
            'role' => [$role1->id, $role2->id],
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }

    public function testShouldFailToValidateWhenRoleFieldIsInvalid()
    {
        $input = [
            'role' => [1],
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenEmailFieldIsNotUniqueAndIdFieldIsNotSpecified()
    {
        $user = factory(User::class)->create();

        $input = [
            'email' => $user->email,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldPassToValidateWhenEmailFieldIsNotUniqueAndIdFieldIsSpecified()
    {
        $user = factory(User::class)->create();

        $input = [
            'id'    => $user->id,
            'email' => $user->email,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }

    public function makeSut(): UserFormLaravelValidator
    {
        return new UserFormLaravelValidator($this->app['validator']);
    }
}
