<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Server;

use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;

interface ServerRepositoryInterface
{
    public function allServers();

    public function serversOfPage($page = 1, $limit = 10);

    public function serverOfId(ServerId $serverId);

    public function remove(Server $server);

    public function save(Server $server);
}
