<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Server;

use Webloyer\Domain\Model\Server\Servers;

/**
 * @codeCoverageIgnore
 */
interface ServersDataTransformer
{
    /**
     * @param Servers $servers
     * @return self
     */
    public function write(Servers $servers): self;
    /**
     * @return mixed
     */
    public function read();
    /**
     * @return ServerDataTransformer
     */
    public function serverDataTransformer(): ServerDataTransformer;
}
