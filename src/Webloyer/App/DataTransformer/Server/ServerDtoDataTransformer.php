<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Server;

use Webloyer\Domain\Model\Server\{
    Server,
    ServerInterest,
};

class ServerDtoDataTransformer implements ServerDataTransformer
{
    /** @var Server */
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
            /** @var string */
            public $id;
            /** @var string */
            public $name;
            /** @var string|null */
            public $description;
            /** @var string */
            public $body;
            /** @var int */
            public $surrogateId;
            /** @var string */
            public $createdAt;
            /** @var string */
            public $updatedAt;
            /**
             * @param string $id
             * @return void
             */
            public function informId(string $id): void
            {
                $this->id = $id;
            }
            /**
             * @param string $name
             * @return void
             */
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            /**
             * @param string|null $description
             * @return void
             */
            public function informDescription(?string $description): void
            {
                $this->description = $description;
            }
            /**
             * @param string $body
             * @return void
             */
            public function informBody(string $body): void
            {
                $this->body = $body;
            }
        };
        $this->server->provide($dto);

        $dto->surrogateId = $this->server->surrogateId();
        assert(!is_null($this->server->createdAt()));
        $dto->createdAt = $this->server->createdAt();
        assert(!is_null($this->server->updatedAt()));
        $dto->updatedAt = $this->server->updatedAt();

        return $dto;
    }
}
