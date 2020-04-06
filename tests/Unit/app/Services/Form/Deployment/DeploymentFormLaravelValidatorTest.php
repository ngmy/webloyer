<?php

namespace Tests\Unit\app\Services\Form\Deployment;

use App\Models\Project;
use App\Models\Server;
use App\Models\User;
use App\Services\Form\Deployment\DeploymentFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class DeploymentFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_FailToValidate_When_ProjectIdFieldIsMissing()
    {
        $user = factory(User::class)->create();

        $input = [
            'task'    => 'deploy',
            'user_id' => $user->id,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_ProjectIdFieldIsInvalid()
    {
        $user = factory(User::class)->create();

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
            'user_id'    => $user->id,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsMissing()
    {
        $user = factory(User::class)->create();
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);

        $input = [
            'project_id' => $project->id,
            'user_id'    => $user->id,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsInvalid()
    {
        $user = factory(User::class)->create();
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);

        $input = [
            'project_id' => $project->id,
            'task'       => 'invalid_task',
            'user_id'    => $server->id,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_UserIdFieldIsMissing()
    {
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);

        $input = [
            'project_id' => $project->id,
            'task'       => 'deploy',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_UserIdFieldIsInvalid()
    {
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);

        $input = [
            'project_id' => $project->id,
            'task'       => 'deploy',
            'user_id'    => 1,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_PassToValidate_When_ProjectIdFieldAndTaskFieldAndUserIdFieldAreValid()
    {
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);
        $user = factory(User::class)->create();

        $input = [
            'project_id' => $project->id,
            'task'       => 'deploy',
            'user_id'    => $user->id,
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }

    public function makeSut(): DeploymentFormLaravelValidator
    {
        return new DeploymentFormLaravelValidator($this->app['validator']);
    }
}
