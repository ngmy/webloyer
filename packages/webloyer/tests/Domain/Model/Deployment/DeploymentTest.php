<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use TestCase;

class DeploymentTest extends TestCase
{
    public function test_Should_GetProjectId()
    {
        $expectedResult = new ProjectId(1);

        $deployment = $this->createDeployment([
            'projectId' => $expectedResult->id(),
        ]);

        $actualResult = $deployment->projectId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDeploymentId()
    {
        $expectedResult = new DeploymentId(1);

        $deployment = $this->createDeployment([
            'deploymentId' => $expectedResult->id(),
        ]);

        $actualResult = $deployment->deploymentId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetTask()
    {
        $expectedResult = new Task('rollback');

        $deployment = $this->createDeployment([
            'task' => $expectedResult->value(),
        ]);

        $actualResult = $deployment->task();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetStatus()
    {
        $expectedResult = new Status(1);

        $deployment = $this->createDeployment([
            'status' => $expectedResult->value(),
        ]);

        $actualResult = $deployment->status();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetMessage()
    {
        $expectedResult = 'some message';

        $deployment = $this->createDeployment([
            'message' => $expectedResult,
        ]);

        $actualResult = $deployment->message();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDeployedUserId()
    {
        $expectedResult = new UserId(1);

        $deployment = $this->createDeployment([
            'deployedUserId' => $expectedResult->id(),
        ]);

        $actualResult = $deployment->deployedUserId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetCreatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $deployment = $this->createDeployment([
            'createdAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $deployment->createdAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUpdatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $deployment = $this->createDeployment([
            'updatedAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $deployment->updatedAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDeployment(),
            $this->createDeployment(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDeployment(),
            $this->createDeployment([
                'deploymentId' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDeployment(array $params = [])
    {
        $projectId = 1;
        $deploymentId = 1;
        $task = 'deploy';
        $status = 2;
        $message = '';
        $deployedUserId = 1;
        $createdAt = '';
        $updatedAt = '';

        extract($params);

        return new Deployment(
            new ProjectId($projectId),
            new DeploymentId($deploymentId),
            new Task($task),
            new Status($status),
            $message,
            new UserId($deployedUserId),
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }
}
