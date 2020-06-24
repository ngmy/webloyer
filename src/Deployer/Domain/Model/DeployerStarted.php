<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishNowDomainEvent,
};

class DeployerStarted implements DomainEvent, PublishNowDomainEvent
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;
    /** @var string */
    private $startDate;

    /**
     * @param string $projectId
     * @param int    $number
     * @param string $startDate
     * @return void
     */
    public function __construct(
        string $projectId,
        int $number,
        string $startDate
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->startDate = $startDate;
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
    public function startDate(): string
    {
        return $this->startDate;
    }
}
