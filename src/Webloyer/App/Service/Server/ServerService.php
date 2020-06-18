<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

use Common\App\Service\ApplicationService;
use Webloyer\App\DataTransformer\Server\{
    ServerDataTransformer,
    ServersDataTransformer,
};
use Webloyer\Domain\Model\Server\{
    Server,
    ServerDoesNotExistException,
    ServerId,
    ServerRepository,
};

abstract class ServerService implements ApplicationService
{
    /** @var ServerRepository */
    protected $serverRepository;
    /** @var ServerDataTransformer */
    protected $serverDataTransformer;
    /** @var ServersDataTransformer */
    protected $serversDataTransformer;

    /**
     * @param ServerRepository       $serverRepository
     * @param ServerDataTransformer  $serverDataTransformer
     * @param ServersDataTransformer $serversDataTransformer
     * @return void
     */
    public function __construct(
        ServerRepository $serverRepository,
        ServerDataTransformer $serverDataTransformer,
        ServersDataTransformer $serversDataTransformer
    ) {
        $this->serverRepository = $serverRepository;
        $this->serverDataTransformer = $serverDataTransformer;
        $this->serversDataTransformer = $serversDataTransformer;
    }

    /**
     * @return ServerDataTransformer
     */
    public function serverDataTransformer(): ServerDataTransformer
    {
        return $this->serverDataTransformer;
    }

    /**
     * @return ServersDataTransformer
     */
    public function serversDataTransformer(): ServersDataTransformer
    {
        return $this->serversDataTransformer;
    }

    /**
     * @param ServerId $id
     * @return Server
     * @throws ServerDoesNotExistException
     */
    protected function getNonNullServer(ServerId $id): Server
    {
        $server = $this->serverRepository->findById($id);
        if (is_null($server)) {
            throw new ServerDoesNotExistException(
                'Server does not exist.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $server;
    }
}
