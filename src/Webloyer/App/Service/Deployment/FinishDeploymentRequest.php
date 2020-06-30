<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

class FinishDeploymentRequest
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
    public function getLog(): string
    {
        return $this->log;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getFinishDate(): string
    {
        return $this->finishDate;
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
     * @param string $log
     * @return self
     */
    public function setLog(string $log): self
    {
        $this->log = $log;
        return $this;
    }

    /**
     * @param int $status
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $finishDate
     * @return self
     */
    public function setFinishDate(string $finishDate): self
    {
        $this->finishDate = $finishDate;
        return $this;
    }
}
