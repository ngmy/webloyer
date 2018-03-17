<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\User as EloquentUser;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentFormLaravelValidator;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Project as EloquentProject;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Server as EloquentServer;
use Tests\Helpers\EloquentFactory;
use TestCase;

class DeploymentFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    private $deploymentFormLaravelValidator;

    public function setUp()
    {
        parent::setUp();

        $this->deploymentFormLaravelValidator = new DeploymentFormLaravelValidator($this->app['validator']);
    }

    public function test_Should_FailToValidate_When_ProjectIdFieldIsMissing()
    {
        EloquentFactory::create(
            EloquentUser::class,
            [
                'email'     => 'user@example.com',
                'password'  => '0123456789',
                'api_token' => '0123456789',
            ]
        );

        $input = [
            'task'    => 'deploy',
            'user_id' => 1,
        ];

        $actualValidateResult = $this->deploymentFormLaravelValidator->with($input)->passes();
        $actualValidateErrors = $this->deploymentFormLaravelValidator->errors();

        $this->assertFalse($actualValidateResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualValidateErrors);
    }

    public function test_Should_FailToValidate_When_ProjectIdFieldIsInvalid()
    {
        EloquentFactory::create(
            EloquentUser::class,
            [
                'email'     => 'user@example.com',
                'password'  => '0123456789',
                'api_token' => '0123456789',
            ]
        );

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
        ];

        $actualValidateResult = $this->deploymentFormLaravelValidator->with($input)->passes();
        $actualValidateErrors = $this->deploymentFormLaravelValidator->errors();

        $this->assertFalse($actualValidateResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualValidateErrors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsMissing()
    {
        $server = EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentProject::class,
            [
                'name'      => 'Project 1',
                'server_id' => $server->id,
                'stage'     => 'staging',
            ]
        );

        EloquentFactory::create(
            EloquentUser::class,
            [
                'email'     => 'user@example.com',
                'password'  => '0123456789',
                'api_token' => '0123456789',
            ]
        );

        $input = [
            'project_id' => 1,
            'user_id'    => 1,
        ];

        $actualValidateResult = $this->deploymentFormLaravelValidator->with($input)->passes();
        $actualValidateErrors = $this->deploymentFormLaravelValidator->errors();

        $this->assertFalse($actualValidateResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualValidateErrors);
    }

    public function test_Should_FailToValidate_When_TaskFieldIsInvalid()
    {
        $server = EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentProject::class,
            [
                'name'      => 'Project 1',
                'server_id' => $server->id,
                'stage'     => 'staging',
            ]
        );

        EloquentFactory::create(
            EloquentUser::class,
            [
                'email'     => 'user@example.com',
                'password'  => '0123456789',
                'api_token' => '0123456789',
            ]
        );

        $input = [
            'project_id' => 1,
            'task'       => 'invalid_task',
            'user_id'    => 1,
        ];

        $actualValidateResult = $this->deploymentFormLaravelValidator->with($input)->passes();
        $actualValidateErrors = $this->deploymentFormLaravelValidator->errors();

        $this->assertFalse($actualValidateResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualValidateErrors);
    }

    public function test_Should_FailToValidate_When_UserIdFieldIsMissing()
    {
        $server = EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentProject::class,
            [
                'name'      => 'Project 1',
                'server_id' => $server->id,
                'stage'     => 'staging',
            ]
        );

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
        ];

        $actualValidateResult = $this->deploymentFormLaravelValidator->with($input)->passes();
        $actualValidateErrors = $this->deploymentFormLaravelValidator->errors();

        $this->assertFalse($actualValidateResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualValidateErrors);
    }

    public function test_Should_FailToValidate_When_UserIdFieldIsInvalid()
    {
        $server = EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentProject::class,
            [
                'name'      => 'Project 1',
                'server_id' => $server->id,
                'stage'     => 'staging',
            ]
        );

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
        ];

        $actualValidateResult = $this->deploymentFormLaravelValidator->with($input)->passes();
        $actualValidateErrors = $this->deploymentFormLaravelValidator->errors();

        $this->assertFalse($actualValidateResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualValidateErrors);
    }

    public function test_Should_PassToValidate_When_ProjectIdFieldAndTaskFieldAndUserIdFieldAreValid()
    {
        $server = EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentProject::class,
            [
                'name'      => 'Project 1',
                'server_id' => $server->id,
                'stage'     => 'staging',
            ]
        );

        EloquentFactory::create(
            EloquentUser::class,
            [
                'email'     => 'user@example.com',
                'password'  => '0123456789',
                'api_token' => '0123456789',
            ]
        );

        $input = [
            'project_id' => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
        ];

        $actualValidateResult = $this->deploymentFormLaravelValidator->with($input)->passes();
        $actualValidateErrors = $this->deploymentFormLaravelValidator->errors();

        $this->assertTrue($actualValidateResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualValidateErrors);
    }
}
