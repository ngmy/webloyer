<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Deployment;

use Webloyer\App\DataTransformer\User\UserDataTransformer;
use Webloyer\Domain\Model\Deployment\Deployment;

/**
 * @codeCoverageIgnore
 */
interface DeploymentDataTransformer
{
    /**
     * @param Deployment $deployment
     * @return self
     */
    public function write(Deployment $deployment): self;
    /**
     * @return mixed
     */
    public function read();
    /**
     * @param UserDataTransformer $userDataTransformer
     * @return self
     */
    public function setUserDataTransformer(UserDataTransformer $userDataTransformer): self;
}
