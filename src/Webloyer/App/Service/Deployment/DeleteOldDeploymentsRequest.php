<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

class DeleteOldDeploymentsRequest
{
    /** @var string */
    private $dateTime;

    /**
     * @return string
     */
    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    /**
     * @param string $dateTime
     * @return self
     */
    public function setNumber(string $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }
}
