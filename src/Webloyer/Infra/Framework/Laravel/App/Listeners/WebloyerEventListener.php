<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Common\Infra\App\Notification\LaravelEventListener;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class WebloyerEventListener extends LaravelEventListener implements ShouldQueue
{
    /** @var string|null */
    public $queue = 'webloyer_listeners';
    /** @var int|null */
    public $timeout = 0;
}
