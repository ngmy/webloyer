<?php

namespace Tests\Unit\app\Services\Api;

use App\Models\Project;
use App\Models\User;
use App\Services\Api\JsonRpc;
use Auth;
use Exception;
use Illuminate\Support\MessageBag;
use InvalidArgumentException;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\MockeryHelper;
use Tests\TestCase;

class JsonRpcTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    protected $mockProjectRepository;

    protected $mockDeploymentForm;

    protected $mockAuthGuard;

    protected $mockProjectModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockDeploymentForm = $this->mock('App\Services\Form\Deployment\DeploymentForm');
        $this->mockAuthGuard = $this->mock('Illuminate\Contracts\Auth\Guard');
        $this->mockProjectModel = $this->mockPartial(Project::class);
    }

    public function test_Should_NotThrowWException_When_DeployProcedureSucceeds()
    {
        try {
            $this->mockAuthGuard
                ->shouldReceive('user')
                ->andReturn(new User());

            Auth::shouldReceive('guard')
                ->andReturn($this->mockAuthGuard);

            $this->mockDeploymentForm
                ->shouldReceive('save')
                ->once()
                ->andReturn(true);

            $this->mockProjectModel
                ->shouldReceive('getLastDeployment');
            $this->mockProjectRepository
                ->shouldReceive('byId')
                ->andReturn($this->mockProjectModel);

            $jsonRpc = new JsonRpc(
                $this->mockProjectRepository,
                $this->mockDeploymentForm
            );

            $jsonRpc->deploy(1);

            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_Should_ThrowWException_When_DeployProcedureFails()
    {
        $this->mockAuthGuard
            ->shouldReceive('user')
            ->andReturn(new User());

        Auth::shouldReceive('guard')
            ->andReturn($this->mockAuthGuard);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockDeploymentForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag());

        $jsonRpc = new JsonRpc(
            $this->mockProjectRepository,
            $this->mockDeploymentForm
        );

        $jsonRpc->deploy(1);
    }

    public function test_Should_NotThrowWException_When_RollbackProcedureSucceeds()
    {
        try {
            $this->mockAuthGuard
                ->shouldReceive('user')
                ->andReturn(new User());

            Auth::shouldReceive('guard')
                ->andReturn($this->mockAuthGuard);

            $this->mockDeploymentForm
                ->shouldReceive('save')
                ->once()
                ->andReturn(true);

            $this->mockProjectModel
                ->shouldReceive('getLastDeployment');
            $this->mockProjectRepository
                ->shouldReceive('byId')
                ->andReturn($this->mockProjectModel);

            $jsonRpc = new JsonRpc(
                $this->mockProjectRepository,
                $this->mockDeploymentForm
            );

            $jsonRpc->deploy(1);

            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_Should_ThrowWException_When_RollbackProcedureFails()
    {
        $this->mockAuthGuard
            ->shouldReceive('user')
            ->andReturn(new User());

        Auth::shouldReceive('guard')
            ->andReturn($this->mockAuthGuard);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockDeploymentForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag());

        $jsonRpc = new JsonRpc(
            $this->mockProjectRepository,
            $this->mockDeploymentForm
        );

        $jsonRpc->rollback(1);
    }
}
