<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

/**
 * @codeCoverageIgnore
 */
interface DeploymentProjection
{
    /**
     * @param Deployment $deployment
     * @return void
     */
    public function project(Deployment $deployment): void;
}
