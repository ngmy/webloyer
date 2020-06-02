<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Enumerable;

/**
 * @method static self queued()
 * @method static self running()
 * @method static self succeeded()
 * @method static self failed()
 */
class DeploymentStatus
{
    use Enumerable;

    /** @var string */
    private const QUEUED = 'queued';
    /** @var string */
    private const RUNNING = 'running';
    /** @var string */
    private const SUCCEEDED = 'succeeded';
    /** @var string */
    private const FAILED = 'failed';

    public function isRequested(): bool
    {
        return $this->value() == self::QUEUED;
    }

    public function isRunning(): bool
    {
        return $this->value() == self::Running;
    }

    public function isCompleted(): bool
    {
        return $this->value() == self::SUCCEEDED
            || $this->value() == self::FAILED;
    }
}
