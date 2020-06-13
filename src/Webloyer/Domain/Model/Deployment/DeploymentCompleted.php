<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\DomainEvent;

class DeploymentCompleted implements DomainEvent
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;
    /** @var string */
    private $task;
    /** @var string */
    private $status;
    /** @var string */
    private $log;
    /** @var string */
    private $userId;

    /**
     * @param string $projectId
     * @param int    $number
     * @param string $task
     * @param string $status
     * @param string $log
     * @param string $userId
     * @return void
     */
    public function __construct(
        string $projectId,
        int $number,
        string $task,
        string $status,
        string $log,
        string $userId
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->task = $task;
        $this->status = $status;
        $this->log = $log;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function projectId(): string
    {
        return $this->projectId;
    }

    /**
     * @return int
     */
    public function number(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function task(): string
    {
        return $this->task;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function log(): string
    {
        return $this->log;
    }

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }
}
