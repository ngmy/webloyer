<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishNowDomainEvent,
};

class DeployerProgressed implements DomainEvent, PublishNowDomainEvent
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;
    /** @var string */
    private $log;

    /**
     * @param string $projectId
     * @param int    $number
     * @param string $log
     * @return void
     */
    public function __construct(
        string $projectId,
        int $number,
        string $log
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->log = $log;
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
}
