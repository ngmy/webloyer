<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\{
    Server,
    ServerId,
};

class GetServerService extends ServerService
{
    /**
     * @param GetServerRequest $request
     * @return Server
     */
    public function execute($request = null)
    {
        $id = new ServerId($request->getId());
        return $this->getNonNullServer($id);
    }
}
