<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\{
    Server,
    Servers,
};

class GetServersService extends ServerService
{
    /**
     * @param GetServersRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $servers = $this->serverRepository->findAllByPage($request->getPage(), $request->getPerPage());
        return array_map(function (Server $server): object {
            return $this->serverDataTransformer->write($server)->read();
        }, $servers->toArray());
    }
}
