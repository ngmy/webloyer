<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Server;

class Servers
{
    /** @var array<int, Server> */
    private $servers;

    /**
     * @param Server ...$servers
     * @return void
     */
    public function __construct(Server ...$servers)
    {
        $this->servers = $servers;
    }

    /**
     * @return array<int, Server>
     */
    public function toArray(): array
    {
        return $this->servers;
    }
}
