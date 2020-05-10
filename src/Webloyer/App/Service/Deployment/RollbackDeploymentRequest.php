<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

class RollbackDeploymentRequest
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;
    /** @var string */
    private $executor;

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
    public function getExecutor(): string
    {
        return $this->executor;
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
     * @param string $executor
     * @return self
     */
    public function setExecutor(string $executor): self
    {
        $this->executor = $executor;
        return $this;
    }
}
