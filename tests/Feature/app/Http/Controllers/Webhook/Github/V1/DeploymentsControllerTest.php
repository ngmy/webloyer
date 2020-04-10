<?php

namespace Tests\Feature\app\Http\Controllers\Webhook\Github\V1;

use App\Http\Middleware\ApplySettings;
use App\Models\Deployment;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\TestCase;

class DeploymentsControllerTest extends TestCase
{
    use ControllerTestHelper;

    protected $mockProjectRepository;

    protected $mockDeploymentForm;

    protected $mockProjectModel;

    protected $mockDeploymentModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->partialMock(User::class);
        $user->shouldReceive('hasPermission')
            ->andReturn(true);
        $this->auth($user);

        $this->mockProjectRepository = $this->mock(ProjectInterface::class);
        $this->mockDeploymentForm = $this->mock(DeploymentForm::class);
        $this->mockProjectModel = $this->partialMock(Project::class);
        $this->mockDeploymentModel = $this->partialMock(Deployment::class);
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

        $response = $this->post('webhook/github/v1/projects/1/deployments');

        $response->assertStatus(200);
    }

    public function test_Should_ReturnStatusCode400_When_StoreProcessFails()
    {
        $project = factory(Project::class)->make([
            'github_webhook_secret' => null,
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
            ->andReturn(new MessageBag());

        $response = $this->post('webhook/github/v1/projects/1/deployments');

        $response->assertStatus(400);
    }
}
