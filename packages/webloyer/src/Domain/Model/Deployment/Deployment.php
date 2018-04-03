<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;

class Deployment extends AbstractEntity
{
    private $projectId;

    private $deploymentId;

    private $task;

    private $status;

    private $message;

    private $deployedUserId;

    private $createdAt;

    private $updatedAt;

    public function __construct(ProjectId $projectId, DeploymentId $deploymentId, Task $task, Status $status, $message, UserId $deployedUserId, Carbon $createdAt = null, Carbon $updatedAt = null)
    {
        $this->setProjectId($projectId);
        $this->setDeploymentId($deploymentId);
        $this->setTask($task);
        $this->setStatus($status);
        $this->setMessage($message);
        $this->setDeployedUserId($deployedUserId);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
    }

    public function projectId()
    {
        return $this->projectId;
    }

    public function deploymentId()
    {
        return $this->deploymentId;
    }

    public function task()
    {
        return $this->task;
    }

    public function status()
    {
        return $this->status;
    }

    public function message()
    {
        return $this->message;
    }

    public function deployedUserId()
    {
        return $this->deployedUserId;
    }

    public function createdAt()
    {
        return $this->createdAt;
    }

    public function updatedAt()
    {
        return $this->updatedAt;
    }

    public function equals($object)
    {
        $equalObjects = false;

        if (!is_null($object) && $object instanceof self) {
            $equalObjects = $this->projectId()->equals($object->projectId()) &&
                $this->deploymentId()->equals($object->deploymentId());
        }

        return $equalObjects;
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    private function setDeploymentId(DeploymentId $deploymentId)
    {
        $this->deploymentId = $deploymentId;

        return $this;
    }

    private function setTask(Task $task)
    {
        $this->task = $task;

        return $this;
    }

    private function setStatus(Status $status)
    {
        $this->status = $status;

        return $this;
    }

    private function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    private function setDeployedUserId(UserId $deployedUserId)
    {
        $this->deployedUserId = $deployedUserId;

        return $this;
    }

    private function setCreatedAt(Carbon $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    private function setUpdatedAt(Carbon $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
