<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\{
    ServerBody,
    ServerDescription,
    ServerDoesNotExistException,
    ServerId,
    ServerName,
};

class UpdateServerService extends ServerService
{
    /**
     * @param UpdateServerRequest $request
     * @return void
     * @throws ServerDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new ServerId($request->getId());
        $name = new ServerName($request->getName());
        $description = new ServerDescription($request->getDescription());
        $body = new ServerBody($request->getBody());
        $server = $this->getNonNullServer($id)
            ->changeName($name)
            ->changeDescription($description)
            ->changeBody($body);
        $this->serverRepository->save($server);
    }
}
