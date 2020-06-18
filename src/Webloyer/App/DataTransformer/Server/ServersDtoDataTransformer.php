<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Server;

use Webloyer\Domain\Model\Server\{
    Server,
    Servers,
};

class ServersDtoDataTransformer implements ServersDataTransformer
{
    /** @var Servers */
    private $servers;
    /** @var ServerDtoDataTransformer */
    private $serverDataTransformer;

    public function __construct(ServerDtoDataTransformer $serverDataTransformer)
    {
        $this->serverDataTransformer = $serverDataTransformer;
    }

    /**
     * @param Servers $servers
     * @return self
     */
    public function write(Servers $servers): self
    {
        $this->servers = $servers;
        return $this;
    }

    /**
     * @return list<object>
     */
    public function read()
    {
        return array_map(function (Server $server): object {
            return $this->serverDataTransformer->write($server)->read();
        }, $this->servers->toArray());
    }

    /**
     * @return ServerDataTransformer
     */
    public function serverDataTransformer(): ServerDataTransformer
    {
        return $this->serverDataTransformer;
    }
}
