<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\ServerId;

class DeleteServerService extends ServerService
{
    /**
     * @param DeleteServerRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $id = new ServerId($request->getId());
        $server = $this->getNonNullServer($id);
        $this->serverRepository->remove($server);
    }
}
