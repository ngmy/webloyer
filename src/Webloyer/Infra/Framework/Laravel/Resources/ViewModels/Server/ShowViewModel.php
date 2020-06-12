<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server;

use Spatie\ViewModels\ViewModel;

class ShowViewModel extends ViewModel
{
    /** @var object */
    private $server;

    /**
     * @param object $server
     * @return void
     */
    public function __construct(object $server)
    {
        $this->server = $server;
    }

    /**
     * @return object
     */
    public function server(): object
    {
        return $this->server;
    }
}
