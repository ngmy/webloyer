<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

class Deployments
{
    /** @var array<int, Deployment> */
    private $deployments;

    /**
     * @param Deployment ...$deployments
     * @return void
     */
    public function __construct(Deployment ...$deployments)
    {
        $this->deployments = $deployments;
    }

    /**
     * @return array<int, Deployment>
     */
    public function toArray(): array
    {
        return $this->deployments;
    }
}
