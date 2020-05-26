<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

class GetServersService extends ServerService
{
    /**
     * @return mixed
     */
    public function execute($request = null)
    {
        $servers = $this->serverRepository->findAll();
        return $this->serversDataTransformer->write($servers)->read();
    }
}
