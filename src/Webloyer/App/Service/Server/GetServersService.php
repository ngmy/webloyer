<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\Servers;

class GetServersService extends ServerService
{
    /**
     * @param GetServersRequest $request
     * @return Servers
     */
    public function execute($request = null)
    {
        return $this->serverRepository->findAllByPage($request->getPage(), $request->getPerPage());
    }
}
