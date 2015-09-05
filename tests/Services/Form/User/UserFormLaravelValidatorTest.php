<?php

use App\Services\Form\User\UserFormLaravelValidator;

use Tests\Helpers\Factory;

class UserFormLaravelValidatorTest extends TestCase {

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

	public function test_Should_PassToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreInvalid()
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

	public function test_Should_PassToValidate_When_PasswordFieldAndPasswordConfirmationFieldAreDifferent()
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

}
