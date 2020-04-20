<?php

declare(strict_types=1);

namespace Webloyer\App\Deployment\Commands;

class DeleteDeploymentCommand
{
    /** @var string */
    private $projectId;
    /** @var int */
    private $number;

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
}
