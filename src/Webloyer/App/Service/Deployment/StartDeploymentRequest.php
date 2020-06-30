<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

class StartDeploymentRequest
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;
    /** @var string */
    private $startDate;

    /**
     * @return string
     */
    public function getProjectId(): string
    {
        return $this->projectId;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @param string $projectId
     * @return self
     */
    public function setProjectId(string $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }

    /**
     * @param int $number
     * @return self
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param string $startDate
     * @return self
     */
    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }
}
