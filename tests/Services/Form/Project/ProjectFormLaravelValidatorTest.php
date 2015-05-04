<?php

use App\Services\Form\Project\ProjectFormLaravelValidator;

class ProjectFormLaravelValidatorTest extends TestCase {

	public function test_Should_FailToValidate_When_RecipePathFieldIsMissing()
	{
		$input = [
			'name' => 'Project 1',
		];

		$form = new ProjectFormLaravelValidator($this->app['validator']);
		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_FailToValidate_When_NameFieldIsMissing()
	{
		$input = [
			'recipe_path' => 'deploy.php',
		];

		$form = new ProjectFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_PassToValidate_When_NameFieldAndRecipePathFieldAreValid()
	{
		$input = [
			'name'        => 'Project 1',
			'recipe_path' => 'deploy.php',
		];

		$form = new ProjectFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertTrue($result, 'Expected validation to succeed.');
		$this->assertEmpty($errors);
	}

}
