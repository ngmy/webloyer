<?php

use App\Services\Form\Deployment\DeploymentFormLaravelValidator;

use Tests\Helpers\Factory;

class DeploymentFormLaravelValidatorTest extends TestCase {

	protected $useDatabase = true;

	public function test_Should_FailToValidate_When_ProjectIdFieldIsMissing()
	{
		$input = [
			'task' => 'deploy',
		];

		$form = new DeploymentFormLaravelValidator($this->app['validator']);
		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_FailToValidate_When_ProjectIdFieldIsInvalid()
	{
		$input = [
			'project_id' => 1,
			'task'       => 'deploy',
		];

		$form = new DeploymentFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_FailToValidate_When_TaskFieldIsMissing()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);

		Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'stage'     => 'staging',
		]);

		$input = [
			'project_id' => 1,
		];

		$form = new DeploymentFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_FailToValidate_When_TaskFieldIsInvalid()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);

		Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'stage'     => 'staging',
		]);

		$input = [
			'project_id' => 1,
			'task'       => 'invalid_task',
		];

		$form = new DeploymentFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertFalse($result, 'Expected validation to fail.');
		$this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
	}

	public function test_Should_PassToValidate_When_ProjectIdFieldAndTaskFieldAreValid()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);

		Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'stage'     => 'staging',
		]);

		$input = [
			'project_id' => 1,
			'task'       => 'deploy',
		];

		$form = new DeploymentFormLaravelValidator($this->app['validator']);

		$result = $form->with($input)->passes();
		$errors = $form->errors();

		$this->assertTrue($result, 'Expected validation to succeed.');
		$this->assertEmpty($errors);
	}

}
