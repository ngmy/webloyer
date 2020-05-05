<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\DomainEventPublisher;
use Common\Domain\Model\Identifiable;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\User\UserEmail;

class Deployment
{
    use Identifiable;

    /** @var ProjectId */
    private $projectId;
    /** @var DeploymentNumber */
    private $number;
    /** @var DeploymentTask */
    private $task;
    /** @var DeploymentStatus */
    private $status;
    /** @var DeploymentLog */
    private $log;
    /** @var UserEmail */
    private $executor;
    /** @var DeploymentCompletionDate|null */
    private $completionDate;

    /**
     * @param string      $projectId
     * @param int         $number
     * @param string      $task
     * @param string      $status
     * @param string      $log
     * @param string      $executor
     * @param string|null $completionDate
     * @return self
     */
    public static function of(
        string $projectId,
        int $number,
        string $task,
        string $status,
        string $log,
        string $executor,
        ?string $completionDate
    ): self {
        return new self(
            new ProjectId($projectId),
            new DeploymentNumber($number),
            DeploymentTask::$task(),
            DeploymentStatus::$status(),
            new DeploymentLog($log),
            new UserEmail($executor),
            isset($completionDate) ? DeploymentCompletionDate::of($completionDate) : null
        );
    }

    /**
     * @param ProjectId                     $projectId
     * @param DeploymentNumber              $number
     * @param DeploymentTask                $task
     * @param DeploymentStatus              $status
     * @param DeploymentLog                 $log
     * @param UserEmail                     $executor
     * @param DeploymentCompletionDate|null $completionDate
     * @return void
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        DeploymentTask $task,
        DeploymentStatus $status,
        DeploymentLog $log,
        UserEmail $executor,
        ?DeploymentCompletionDate $completionDate
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->task = $task;
        $this->status = $status;
        $this->log = $log;
        $this->executor = $executor;
        $this->completationDate = $complationDate;

        if (!$this->isIdentified()) {
            DomainEventPublisher::getInstance()->publish(
                new DeploymentWasCreatedEvent($projectId, $number, $task)
            );
        }
    }

    /**
     * @return string
     */
    public function projectId(): string
    {
        return $this->projectId->value();
    }

    /**
     * @return int
     */
    public function number(): int
    {
        return $this->number->value();
    }

    /**
     * @return string
     */
    public function task(): string
    {
        return $this->task->value();
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status->value();
    }

    /**
     * @return string
     */
    public function log(): string
    {
        return $this->log->value();
    }

    /**
     * @return string
     */
    public function executor(): string
    {
        return $this->executor->value();
    }

    public function completionDate(): ?string
    {
        return isset($this->completionDate) ? $this->completionDate->toString() : null;
    }

    /**
     * @param string $status
     * @return self
     */
    public function changeStatus(string $status): self
    {
        $this->status = DeploymentStatus::$status();
        return $this;
    }

    /**
     * @param string $log
     * @return self
     */
    public function changeLog(string $log): self
    {
        $this->log = new DeploymentLog($log);
        return $this;
    }

    public function changeCompletionDate(string $completionDate): self
    {
        $this->log = DeploymentCompletionDate::of($completionDate);
        return $this;
    }

    public function complete(string $status): void
    {
        $this->changeStatus($status);
        $this->changeCompletionDate(DeploymentCompletionDate::now());
        DomainEventPublisher::getInstance()->publish(
            new DeploymentWasCompletedEvent(
                $this->projectId,
                $this->number,
                $this->task,
                $this->status,
                $this->log,
                $this->completionDate
            )
        );
    }

    /**
     * @param DeploymentInterest $interest
     * @return void
     */
    public function provide(DeploymentInterest $interest): void
    {
        $interest->informProjectId($this->projectId());
        $interest->informNumber($this->number());
        $interest->informTask($this->task());
        $interest->informStatus($this->status());
        $interest->informLog($this->log());
        $interest->informExecutor($this->executor());
        $interest->informCompletionDate($this->completionDate());
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        $equalObjects = false;

        if ($object instanceof self) {
            $equalObjects = $object->projectId == $this->projectId
                && $object->number == $this->number;
        }

        return $equalObjects;
    }
}
