<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\{
    ServerDoesNotExistException,
    ServerId,
};

class DeleteServerService extends ServerService
{
    /**
     * @param DeleteServerRequest $request
     * @return void
     * @throws ServerDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new ServerId($request->getId());
        $server = $this->getNonNullServer($id);
        $this->serverRepository->remove($server);
    }
}
