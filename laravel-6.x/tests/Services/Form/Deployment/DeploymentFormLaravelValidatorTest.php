<?php

use App\Services\Form\Deployment\DeploymentFormLaravelValidator;

use Tests\Helpers\Factory;

class DeploymentFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_FailToValidate_When_ProjectIdFieldIsMissing()
    {
        Factory::create('App\Models\User', [
            'email'     => 'user@example.com',
            'password'  => '0123456789',
            'api_token' => '0123456789',
        ]);

        $input = [
            'task'    => 'deploy',
            'user_id' => 1,
        ];

        $form = new DeploymentFormLaravelValidator($this->app['validator']);
        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }

    public function test_Should_FailToValidate_When_ProjectIdFieldIsInvalid()
    {
        Factory::create('App\Models\User', [
            'email'     => 'user@example.com',
            'password'  => '0123456789',
            'api_token' => '0123456789',
        ]);

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
        ];

        $form = new DeploymentFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsMissing()
    {
        $arrangedServer = Factory::create('App\Models\Server', [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create('App\Models\Project', [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        Factory::create('App\Models\User', [
            'email'     => 'user@example.com',
            'password'  => '0123456789',
            'api_token' => '0123456789',
        ]);

        $input = [
            'project_id' => 1,
            'user_id'    => 1,
        ];

        $form = new DeploymentFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsInvalid()
    {
        $arrangedServer = Factory::create('App\Models\Server', [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create('App\Models\Project', [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        Factory::create('App\Models\User', [
            'email'     => 'user@example.com',
            'password'  => '0123456789',
            'api_token' => '0123456789',
        ]);

        $input = [
            'project_id' => 1,
            'task'       => 'invalid_task',
            'user_id'    => 1,
        ];

        $form = new DeploymentFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }

    public function test_Should_FailToValidate_When_UserIdFieldIsMissing()
    {
        $arrangedServer = Factory::create('App\Models\Server', [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create('App\Models\Project', [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

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

    public function test_Should_FailToValidate_When_UserIdFieldIsInvalid()
    {
        $arrangedServer = Factory::create('App\Models\Server', [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create('App\Models\Project', [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
        ];

        $form = new DeploymentFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }

    public function test_Should_PassToValidate_When_ProjectIdFieldAndTaskFieldAndUserIdFieldAreValid()
    {
        $arrangedServer = Factory::create('App\Models\Server', [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create('App\Models\Project', [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        Factory::create('App\Models\User', [
            'email'     => 'user@example.com',
            'password'  => '0123456789',
            'api_token' => '0123456789',
        ]);

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
        ];

        $form = new DeploymentFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }
}
