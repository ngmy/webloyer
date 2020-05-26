<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Webloyer\App\DataTransformer\Server\ServerDataTransformer;
use Webloyer\Domain\Model\Server\ServerId;

class GetServerService extends ServerService
{
    /**
     * @param GetServerRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $id = new ServerId($request->getId());
        $server = $this->getNonNullServer($id);
        return $this->serverDataTransformer->write($server)->read();
    }
}
