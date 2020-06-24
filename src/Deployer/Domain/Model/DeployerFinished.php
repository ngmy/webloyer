<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishNowDomainEvent,
};

class DeployerFinished implements DomainEvent, PublishNowDomainEvent
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;
    /** @var string */
    private $log;
    /** @var int */
    private $status;
    /** @var string */
    private $finishDate;

    /**
     * @param string $projectId
     * @param int    $number
     * @param string $log
     * @param int    $status
     * @param string $finishDate
     * @return void
     */
    public function __construct(
        string $projectId,
        int $number,
        string $log,
        int $status,
        string $finishDate
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->log = $log;
        $this->status = $status;
        $this->finishDate = $finishDate;
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
    public function log(): string
    {
        return $this->log;
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function finishDate(): string
    {
        return $this->finishDate;
    }
}
