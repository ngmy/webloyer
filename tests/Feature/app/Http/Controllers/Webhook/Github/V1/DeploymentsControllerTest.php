<?php

namespace Tests\Feature\app\Http\Controllers\Webhook\Github\V1;

use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\Factory;
use Tests\Helpers\MockeryHelper;
use Tests\TestCase;

class DeploymentsControllerTest extends \TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    protected $mockProjectRepository;

    protected $mockDeploymentForm;

    protected $mockProjectModel;

    protected $mockDeploymentModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(\App\Http\Middleware\ApplySettings::class, new DummyMiddleware);

        \Session::start();

        $user = $this->mockPartial('App\Models\User');
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockDeploymentForm = $this->mock('App\Services\Form\Deployment\DeploymentForm');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
        $this->mockDeploymentModel = $this->mockPartial('App\Models\Deployment');
    }

    public function test_Should_ReturnStatusCode200_When_StoreProcessSucceeds()
    {
        $project = $this->mockProjectModel
            ->shouldReceive('getLastDeployment')
            ->once()
            ->andReturn($this->mockDeploymentModel)
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->post('webhook/github/v1/projects/1/deployments');

        $this->assertResponseOK();
    }

    public function test_Should_ReturnStatusCode400_When_StoreProcessFails()
    {
        $project = Factory::build('App\Models\Project', [
            'id'                     => 1,
            'name'                   => 'Project 1',
            'github_webhook_user_id' => 1,
            'created_at'             => new \Carbon\Carbon,
            'updated_at'             => new \Carbon\Carbon,
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockDeploymentForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new \Illuminate\Support\MessageBag);

        $this->post('webhook/github/v1/projects/1/deployments');

        $this->assertResponseStatus(400);
    }
}
