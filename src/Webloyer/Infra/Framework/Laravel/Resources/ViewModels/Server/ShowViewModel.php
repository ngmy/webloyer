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

    /**
     * @return bool
     */
    public function isServerHasProject(): bool
    {
        return !empty($this->server->projects);
    }

    /**
     * @return string
     */
    public function serverProjectCount(): string
    {
        return number_format(count($this->server->projects));
    }
}
