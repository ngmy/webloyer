<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Server;

use Webloyer\Domain\Model\Server\{
    Server,
    ServerInterest,
};

class ServerDtoDataTransformer implements ServerDataTransformer
{
    private $server;

    /**
     * @param Server $server
     * @return self
     */
    public function write(Server $server): self
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return object
     */
    public function read()
    {
        $dto = new class implements ServerInterest {
            public function informId(string $id): void
            {
                $this->id = $id;
            }
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            public function informDescription(?string $description): void
            {
                $this->description = $description;
            }
            public function informBody(string $body): void
            {
                $this->body = $body;
            }
        };
        $this->server->provide($dto);
        return $dto;
    }
}
