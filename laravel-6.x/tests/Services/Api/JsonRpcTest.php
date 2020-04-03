<?php

use App\Services\Api\JsonRpc;

class JsonRpcTest extends TestCase
{
    use Tests\Helpers\ControllerTestHelper;

    use Tests\Helpers\MockeryHelper;

    protected $mockProjectRepository;

    protected $mockDeploymentForm;

    protected $mockAuthGuard;

    protected $mockProjectModel;

    public function setUp()
    {
        parent::setUp();

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockDeploymentForm = $this->mock('App\Services\Form\Deployment\DeploymentForm');
        $this->mockAuthGuard = $this->mock('Illuminate\Contracts\Auth\Guard');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
    }

    public function test_Should_NotThrowWException_When_DeployProcedureSucceeds()
    {
        try {
            $this->mockAuthGuard
                ->shouldReceive('user')
                ->andReturn(new App\Models\User);

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
            ->andReturn(new App\Models\User);

        Auth::shouldReceive('guard')
            ->andReturn($this->mockAuthGuard);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockDeploymentForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new Illuminate\Support\MessageBag);

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
                ->andReturn(new App\Models\User);

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
            ->andReturn(new App\Models\User);

        Auth::shouldReceive('guard')
            ->andReturn($this->mockAuthGuard);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockDeploymentForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new Illuminate\Support\MessageBag);

        $jsonRpc = new JsonRpc(
            $this->mockProjectRepository,
            $this->mockDeploymentForm
        );

        $jsonRpc->rollback(1);
    }
}
