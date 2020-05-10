<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\Servers;

class GetAllServersService extends ServerService
{
    /**
     * @return Servers
     */
    public function execute($request = null)
    {
        return $this->serverRepository->findAll();
    }
}
