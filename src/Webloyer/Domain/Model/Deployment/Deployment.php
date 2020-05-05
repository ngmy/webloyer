<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\DomainEventPublisher;
use Common\Domain\Model\Identifiable;
use LogicException;
use Webloyer\Domain\Model\Project\Project;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Recipe\Recipes;
use Webloyer\Domain\Model\Server\Server;
use Webloyer\Domain\Model\User\User;
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
    /** @var DeploymentStartDate */
    private $startDate;
    /** @var DeploymentFinishDate|null */
    private $finishDate;

    /**
     * @param string      $projectId
     * @param int         $number
     * @param string      $task
     * @param string      $status
     * @param string      $log
     * @param string      $executor
     * @param string      $startDate
     * @param string|null $finishDate
     * @return self
     */
    public static function of(
        string $projectId,
        int $number,
        string $task,
        string $status,
        string $log,
        string $executor,
        string $startDate,
        ?string $finishDate
    ): self {
        return new self(
            new ProjectId($projectId),
            new DeploymentNumber($number),
            DeploymentTask::$task(),
            DeploymentStatus::$status(),
            new DeploymentLog($log),
            new UserEmail($executor),
            DeplotmentStartDate::of($startDate),
            isset($finishDate) ? DeploymentFinishDate::of($finishDate) : null
        );
    }

    /**
     * @param ProjectId                 $projectId
     * @param DeploymentNumber          $number
     * @param DeploymentTask            $task
     * @param DeploymentStatus          $status
     * @param DeploymentLog             $log
     * @param UserEmail                 $executor
     * @param DeploymentStartDate       $startDate
     * @param DeploymentFinishDate|null $finishDate
     * @return void
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        DeploymentTask $task,
        DeploymentStatus $status,
        DeploymentLog $log,
        UserEmail $executor,
        DeploymentStartDate $startDate,
        ?DeploymentFinishDate $finishDate
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->task = $task;
        $this->status = $status;
        $this->log = $log;
        $this->executor = $executor;
        $this->startDate = $startDate;
        $this->finishDate = $finishDate;
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

    public function startDate(): string
    {
        return $this->startDate->toString();
    }

    public function finishDate(): ?string
    {
        return isset($this->finishDate) ? $this->finishDate->toString() : null;
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

    public function appendLog(string $log): self
    {
        $this->log = $this->log->append($log);
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

    public function changeFinishDate(string $finishDate): self
    {
        $this->log = DeploymentFinishDate::of($finishDate);
        return $this;
    }

    public function start(
        Project $project,
        Recipes $recipes,
        Server $server,
        User $executor
    ): void {
        DomainEventPublisher::getInstance()->publish(
            new DeploymentWasStartedEvent(
                $this,
                $project,
                $recipes,
                $server,
                $executor
            )
        );
    }

    public function finish(
        Project $project,
        Recipes $recipes,
        Server $server,
        User $executor
    ): void {
        if (!$this->status->isFinished()) {
            throw new LogicException();
        }
        if (is_null($this->finishDate)) {
            throw new LogicException();
        }
        DomainEventPublisher::getInstance()->publish(
            new DeploymentWasFinishedEvent(
                $this,
                $project,
                $recipes,
                $server,
                $executor
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
        $interest->informStartDate($this->startDate());
        $interest->informFinishDate($this->finishDate());
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
