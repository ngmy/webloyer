<?php

use App\Services\Form\User\UserFormLaravelValidator;

use Tests\Helpers\Factory;

class UserFormLaravelValidatorTest extends TestCase {

	protected $useDatabase = true;

	public function test_Should_PassToValidate_When_NameFieldIsValid()
	{
		$input = [
			'name' => 'User 1',
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertTrue($result, 'Expected validation to succeed.');
		$this->assertEmpty($errors);
	}

	public function test_Should_PassToValidate_When_EmailFieldIsValid()
	{
		$input = [
			'email' => 'user1@example.com',
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertTrue($result, 'Expected validation to succeed.');
		$this->assertEmpty($errors);
	}

	public function test_Should_FailToValidate_When_EmailFieldIsInvalid()
	{
		$input = [
			'email' => 'invalid',
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_PassToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreValid()
	{
		$input = [
			'password'              => '12345678',
			'password_confirmation' => '12345678',
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertTrue($result, 'Expected validation to succeed.');
		$this->assertEmpty($errors);
	}

	public function test_Should_FailToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreInvalid()
	{
		$input = [
			'password'              => '1234567',
			'password_confirmation' => '1234567',
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_FailToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreDifferent()
	{
		$input = [
			'password'              => '12345678',
			'password_confirmation' => '23456789',
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_PassToValidate_When_RoleFieldIsValid()
	{
		Factory::create('Kodeine\Acl\Models\Eloquent\Role', [
			'name'        => 'Role 1',
			'slug'        => 'role_1',
			'description' => '',
		]);

		Factory::create('Kodeine\Acl\Models\Eloquent\Role', [
			'name'        => 'Role 2',
			'slug'        => 'role_2',
			'description' => '',
		]);

		$input = [
			'role' => [1, 2],
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertTrue($result, 'Expected validation to succeed.');
		$this->assertEmpty($errors);
	}

	public function test_Should_FailToValidate_When_RoleFieldIsInvalid()
	{
		$input = [
			'role' => [1],
		];

		$form = new UserFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

}
