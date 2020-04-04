<?php

namespace Tests\Unit\app\Services\Form\Deployment;

use App\Models\Project;
use App\Models\Server;
use App\Models\User;
use App\Services\Form\Deployment\DeploymentFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Tests\Helpers\Factory;
use Tests\TestCase;

class DeploymentFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_FailToValidate_When_ProjectIdFieldIsMissing()
    {
        Factory::create(User::class, [
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_ProjectIdFieldIsInvalid()
    {
        Factory::create(User::class, [
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsMissing()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create(Project::class, [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        Factory::create(User::class, [
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsInvalid()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create(Project::class, [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        Factory::create(User::class, [
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_UserIdFieldIsMissing()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create(Project::class, [
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_UserIdFieldIsInvalid()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create(Project::class, [
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_PassToValidate_When_ProjectIdFieldAndTaskFieldAndUserIdFieldAreValid()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        Factory::create(Project::class, [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        Factory::create(User::class, [
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
