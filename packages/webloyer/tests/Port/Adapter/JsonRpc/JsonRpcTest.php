<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc;

use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId as IdentityAccessUserId;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\JsonRpc;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm;
use TestCase;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\MockeryHelper;

class JsonRpcTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    private $deploymentForm;

    private $deploymentService;

    private $authGuard;

    public function setUp()
    {
        parent::setUp();

        $this->deploymentForm = $this->mock(DeploymentForm::class);
        $this->deploymentService = $this->mock(DeploymentService::class);
        $this->authGuard = $this->mock(Guard::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_NotThrowWException_When_DeployProcedureSucceeds()
    {
        $projectId = 1;
        $user = $this->createUser();
        $lastDeployment = $this->createDeployment();
        $expectedResult = json_encode([
            'project_id' => $lastDeployment->projectId()->id(),
            'deployment_id' => $lastDeployment->deploymentId()->id(),
            'task' => $lastDeployment->task()->value(),
            'status' => $lastDeployment->status()->value(),
            'message' => $lastDeployment->message(),
            'deployed_user_id' => $lastDeployment->deployedUserId()->id(),
        ]);

        Auth::shouldReceive('guard')
            ->with('api')
            ->andReturn($this->authGuard);
        $this->authGuard
            ->shouldReceive('user')
            ->withNoArgs()
            ->andReturn($user);
        $this->deploymentForm
            ->shouldReceive('save')
            ->andReturn(true)
            ->once();
        $this->deploymentService
            ->shouldReceive('getLastDeployment')
            ->with($projectId)
            ->andReturn($lastDeployment)
            ->once();

        $jsonRpc = $this->createJsonRpc();

        $actualResult = $jsonRpc->deploy($projectId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_Should_ThrowWException_When_DeployProcedureFails()
    {
        $projectId = 1;
        $user = $this->createUser();

        Auth::shouldReceive('guard')
            ->with('api')
            ->andReturn($this->authGuard);
        $this->authGuard
            ->shouldReceive('user')
            ->withNoArgs()
            ->andReturn($user);
        $this->deploymentForm
            ->shouldReceive('save')
            ->andReturn(false)
            ->once();

        $this->deploymentForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $jsonRpc = $this->createJsonRpc();

        $jsonRpc->deploy($projectId);
    }

    public function test_Should_NotThrowWException_When_RollbackProcedureSucceeds()
    {
        $projectId = 1;
        $user = $this->createUser();
        $lastDeployment = $this->createDeployment();
        $expectedResult = json_encode([
            'project_id' => $lastDeployment->projectId()->id(),
            'deployment_id' => $lastDeployment->deploymentId()->id(),
            'task' => $lastDeployment->task()->value(),
            'status' => $lastDeployment->status()->value(),
            'message' => $lastDeployment->message(),
            'deployed_user_id' => $lastDeployment->deployedUserId()->id(),
        ]);

        Auth::shouldReceive('guard')
            ->with('api')
            ->andReturn($this->authGuard);
        $this->authGuard
            ->shouldReceive('user')
            ->withNoArgs()
            ->andReturn($user);
        $this->deploymentForm
            ->shouldReceive('save')
            ->andReturn(true)
            ->once();
        $this->deploymentService
            ->shouldReceive('getLastDeployment')
            ->with($projectId)
            ->andReturn($lastDeployment)
            ->once();

        $jsonRpc = $this->createJsonRpc();

        $actualResult = $jsonRpc->rollback($projectId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_Should_ThrowWException_When_RollbackProcedureFails()
    {
        $projectId = 1;
        $user = $this->createUser();

        Auth::shouldReceive('guard')
            ->with('api')
            ->andReturn($this->authGuard);
        $this->authGuard
            ->shouldReceive('user')
            ->withNoArgs()
            ->andReturn($user);
        $this->deploymentForm
            ->shouldReceive('save')
            ->andReturn(false)
            ->once();

        $this->deploymentForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $jsonRpc = $this->createJsonRpc();

        $jsonRpc->rollback($projectId);
    }

    private function createJsonRpc(array $params = [])
    {
        $deploymentForm = $this->deploymentForm;
        $deploymentService = $this->deploymentService;

        extract($params);

        return new JsonRpc(
            $deploymentForm,
            $deploymentService
        );
    }

    private function createUser(array $params = [])
    {
        $userId = 1;

        extract($params);

        $user = $this->mock(User::class);

        $user->shouldReceive('userId')->andReturn(new IdentityAccessUserId($userId));

        return $user;
    }

    private function createDeployment(array $params = [])
    {
        $projectId = 1;
        $deploymentId = 1;
        $task = 'deploy';
        $status = 0;
        $message = '';
        $deployedUserId = 1;

        extract($params);

        $deployment = $this->mock(Deployment::class);

        $deployment->shouldReceive('projectId')->andReturn(new ProjectId($projectId));
        $deployment->shouldReceive('deploymentId')->andReturn(new DeploymentId($deploymentId));
        $deployment->shouldReceive('task')->andReturn(new Task($task));
        $deployment->shouldReceive('status')->andReturn(new Status($status));
        $deployment->shouldReceive('message')->andReturn($message);
        $deployment->shouldReceive('deployedUserId')->andReturn(new UserId($deployedUserId));

        return $deployment;
    }
}
