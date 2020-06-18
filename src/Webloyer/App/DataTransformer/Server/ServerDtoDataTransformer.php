<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Server;

use Webloyer\App\DataTransformer\Project\ProjectsDataTransformer;
use Webloyer\Domain\Model\Server\{
    Server,
    ServerId,
    ServerInterest,
    ServerService,
};

class ServerDtoDataTransformer implements ServerDataTransformer
{
    /** @var Server */
    private $server;
    /** @var ServerService */
    private $serverService;
    /** @var ProjectsDataTransformer */
    private $projectsDataTransformer;

    /**
     * @param ServerService $serverService
     * @return void
     */
    public function __construct(ServerService $serverService)
    {
        $this->serverService = $serverService;
    }

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
            /** @var list<object>|null */
            public $projects;
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

        if (isset($this->projectsDataTransformer)) {
            $projects = $this->serverService->projectsFrom(new ServerId($this->server->id()));
            $dto->projects = $this->projectsDataTransformer->write($projects)->read();
        }

        $dto->surrogateId = $this->server->surrogateId();
        assert(!is_null($this->server->createdAt()));
        $dto->createdAt = $this->server->createdAt();
        assert(!is_null($this->server->updatedAt()));
        $dto->updatedAt = $this->server->updatedAt();

        return $dto;
    }

    /**
     * @param ProjectsDataTransformer $projectsDataTransformer
     * @return self
     */
    public function setProjectsDataTransformer(ProjectsDataTransformer $projectsDataTransformer): self
    {
        $this->projectsDataTransformer = $projectsDataTransformer;
        return $this;
    }
}
