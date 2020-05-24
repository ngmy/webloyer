<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\App\DataTransformer\Server\ServersDataTransformer;
use Webloyer\Domain\Model\Server\Servers;

class GetServersService extends ServerService
{
    /**
     * @param GetServersRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $servers = $this->serverRepository->findAllByPage($request->getPage(), $request->getPerPage());
        return $this->serversDataTransformer->write($servers)->read();
    }
}
