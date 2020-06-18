<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\App\DataTransformer\Server\ServerDataTransformer;
use Webloyer\Domain\Model\Server\{
    ServerDoesNotExistException,
    ServerId,
};

class GetServerService extends ServerService
{
    /**
     * @param GetServerRequest $request
     * @return mixed
     * @throws ServerDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new ServerId($request->getId());
        $server = $this->getNonNullServer($id);
        return $this->serverDataTransformer->write($server)->read();
    }
}
