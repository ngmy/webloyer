<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server;

use Spatie\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
    private $server;

    public function __construct(object $server)
    {
        $this->server = $server;
    }

    public function server(): object
    {
        return $this->server;
    }
}
