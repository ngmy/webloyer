<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\Domain\Model\Server\Server;

class CreateServerService extends ServerService
{
    /**
     * @param CreateServerRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $server = Server::of(
            $this->serverRepository->nextId()->value(),
            $request->getName(),
            $request->getDescription(),
            $request->getBody()
        );
        $this->serverRepository->save($server);
    }
}
