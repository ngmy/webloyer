<?php

declare(strict_types=1);

namespace Deployer\Infra\Framework\Laravel\App\Listeners;

use Common\Infra\App\Notification\LaravelEventListener;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class DeployerEventListener extends LaravelEventListener implements ShouldQueue
{
    /** @var string|null */
    public $queue = 'deployer_listeners';
    /** @var int|null */
    public $timeout = 0;
}
