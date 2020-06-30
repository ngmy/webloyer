<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

class ProgressDeploymentRequest
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;
    /** @var string */
    private $log;

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
}
