<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployment;

use DateTimeImmutable;
use Mockery;
use Ngmy\Webloyer\Common\QueryObject\Direction;
use Ngmy\Webloyer\Common\QueryObject\Order;
use Ngmy\Webloyer\Common\QueryObject\Pagination;
use Ngmy\Webloyer\Common\QueryObject\QueryObject;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentCriteria;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\OldDeploymentSpecification;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use Tests\Helpers\MockeryHelper;
use TestCase;

class DeploymentServiceTest extends TestCase
{
    use MockeryHelper;

    private $deploymentService;

    private $projectService;

    private $deploymentRepository;

    private $inputForGetDeploymentsByPage = [
        'page'    => 1,
        'perPage' => 10,
    ];

    private $inputForSaveDeployment = [
        'projectId'       => 1,
        'deploymentId'    => 1,
        'task'            => Task::ENUM['deploy'],
        'processExitCode' => Status::ENUM['success'],
        'message'         => '',
        'deployedUserId'  => 1,
    ];

    public function setUp()
    {
        parent::setUp();

        $this->projectService = $this->mock(ProjectService::class);
        $this->deploymentRepository = $this->mock(DeploymentRepositoryInterface::class);
        $this->deploymentService = new DeploymentService(
            $this->projectService,
            $this->deploymentRepository
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_GetNextIdentity()
    {
        $projectId = 1;
        $project = $this->mock(Project::class);
        $expectedResult = true;
        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($projectId)
            ->andReturn($project)
            ->once();
        $this->deploymentRepository
            ->shouldReceive('nextIdentity')
            ->with($project)
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->deploymentService->getNextIdentity($projectId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDeploymentsByPage_When_PageAndPerPageIsNotSpecified()
    {
        $this->checkGetDeploymentsByPage(null, null, 1, 10);
    }

    public function test_Should_GetDeploymentsByPage_When_PageAndPerPageIsSpecified()
    {
        $this->checkGetDeploymentsByPage(2, 20, 2, 20);
    }

    public function test_Should_GetDeploymentById()
    {
        $projectId = 1;
        $deploymentId = 1;
        $project = $this->mock(Project::class);
        $expectedResult = true;
        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($projectId)
            ->andReturn($project)
            ->once();
        $this->deploymentRepository
            ->shouldReceive('deploymentOfId')
            ->with(
                Mockery::on(function ($arg) use ($project) {
                    return $arg == $project;
                }),
                Mockery::on(function ($arg) use ($deploymentId) {
                    return $arg == new DeploymentId($deploymentId);
                })
            )
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->deploymentService->getDeploymentById($projectId, $deploymentId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetLastDeployment_When_LastDeploymentExists()
    {
        $projectId = 1;
        $expectedResult = true;
        $this->deploymentRepository
            ->shouldReceive('deployments')
            ->with(Mockery::on(function ($arg) use ($projectId) {
                $criteria = new DeploymentCriteria(new ProjectId($projectId));
                $order = new Order('deployments.created_at', Direction::desc());
                $pagination = new Pagination(1, 1);
                $queryObject = new QueryObject();
                $queryObject->setCriteria($criteria)
                    ->addOrder($order)
                    ->setPagination($pagination);
                return $arg == $queryObject;
            }))
            ->andReturn(collect([$expectedResult]))
            ->once();

        $actualResult = $this->deploymentService->getLastDeployment($projectId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetLastDeployment_When_LastDeploymentNotExists()
    {
        $projectId = 1;
        $this->deploymentRepository
            ->shouldReceive('deployments')
            ->with(Mockery::on(function ($arg) use ($projectId) {
                $criteria = new DeploymentCriteria(new ProjectId($projectId));
                $order = new Order('deployments.created_at', Direction::desc());
                $pagination = new Pagination(1, 1);
                $queryObject = new QueryObject();
                $queryObject->setCriteria($criteria)
                    ->addOrder($order)
                    ->setPagination($pagination);
                return $arg == $queryObject;
            }))
            ->andReturn(collect([]))
            ->once();

        $actualResult = $this->deploymentService->getLastDeployment($projectId);

        $this->assertNull($actualResult);
    }

    public function test_Should_SaveDeployment()
    {
        $this->deploymentRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveDeployment);
                $deployment = new Deployment(
                    new ProjectId($projectId),
                    new DeploymentId($deploymentId),
                    new Task($task),
                    Status::fromProcessExitCode($processExitCode),
                    $message,
                    new UserId($deployedUserId),
                    null,
                    null
                );
                return $arg == $deployment;
            }))
            ->once();

        extract($this->inputForSaveDeployment);

        $this->deploymentService->saveDeployment(
            $projectId,
            $deploymentId,
            $task,
            $processExitCode,
            $message,
            $deployedUserId
        );

        $this->assertTrue(true);
    }

    public function test_Should_RemoveOldDeployments_When_OldDeploymentsDoesExist()
    {
        $projectId = 1;
        $project = $this->mock(Project::class);
        $currentDate = $this->mock(DateTimeImmutable::class);
        $spec = new OldDeploymentSpecification(
            $project,
            $currentDate
        );
        $deployments = [$this->mock(Deployment::class)];
        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($projectId)
            ->andReturn($project)
            ->once();
        $this->deploymentRepository
            ->shouldReceive('satisfyingDeployments')
            ->with(Mockery::on(function ($arg) use($spec) {
                return $arg == $spec;
            }))
            ->andReturn($deployments)
            ->once();
        $this->deploymentRepository
            ->shouldReceive('removeAll')
            ->with($deployments)
            ->once();

        $this->deploymentService->removeOldDeployments($projectId, $currentDate);

        $this->assertTrue(true);
    }

    public function test_Should_RemoveOldDeployments_When_OldDeploymentsDoesNotExist()
    {
        $projectId = 1;
        $project = $this->mock(Project::class);
        $currentDate = $this->mock(DateTimeImmutable::class);
        $spec = new OldDeploymentSpecification(
            $project,
            $currentDate
        );
        $deployments = [];
        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($projectId)
            ->andReturn($project)
            ->once();
        $this->deploymentRepository
            ->shouldReceive('satisfyingDeployments')
            ->with(Mockery::on(function ($arg) use($spec) {
                return $arg == $spec;
            }))
            ->andReturn($deployments)
            ->once();
        $this->deploymentRepository
            ->shouldReceive('removeAll')
            ->never();

        $this->deploymentService->removeOldDeployments($projectId, $currentDate);

        $this->assertTrue(true);
    }

    private function checkGetDeploymentsByPage($inputPage, $inputPerPage, $expectedPage, $expectedPerPage)
    {
        $this->inputForGetDeploymentsByPage['page'] = $inputPage;
        $this->inputForGetDeploymentsByPage['perPage'] = $inputPerPage;

        $projectId = 1;
        $expectedResult = true;
        $this->deploymentRepository
            ->shouldReceive('deployments')
            ->with(Mockery::on(function ($arg) use ($projectId, $expectedPage, $expectedPerPage) {
                $criteria = new DeploymentCriteria(new ProjectId($projectId));
                $order = new Order('deployments.created_at', Direction::desc());
                $pagination = new Pagination($expectedPage, $expectedPerPage);
                $queryObject = new QueryObject();
                $queryObject->setCriteria($criteria)
                    ->addOrder($order)
                    ->setPagination($pagination);
                return $arg == $queryObject;
            }))
            ->once()
            ->andReturn($expectedResult);

        extract($this->inputForGetDeploymentsByPage);

        if (isset($page) && isset($perPage)) {
            $actualResult = $this->deploymentService->getDeploymentsByPage($projectId, $page, $perPage);
        } elseif (isset($page)) {
            $actualResult = $this->deploymentService->getDeploymentsByPage($projectId, $page);
        } else {
            $actualResult = $this->deploymentService->getDeploymentsByPage($projectId);
        }

        $this->assertEquals($expectedResult, $actualResult);
    }
}
