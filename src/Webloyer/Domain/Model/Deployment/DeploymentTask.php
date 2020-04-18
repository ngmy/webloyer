<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Enumerable;

/**
 * @method static self deploy()
 * @method static self rollback()
 */
class DeploymentTask
{
    use Enumerable;

    /** @var string */
    private const DEPLOY = 'deploy';
    /** @var string */
    private const ROLLBACK = 'rollback';
}
