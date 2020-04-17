<?php

declare(strict_types=1);

namespace Webloyer\App\Server;

use InvalidArgumentException;
use Webloyer\App\Server\Commands;
use Webloyer\Domain\Model\Server;

class ServerService
{
    /** @var Server\ServerRepository */
    private $serverRepository;

    /**
     * @param Server\ServerRepository $serverRepository
     * @return void
     */
    public function __construct(Server\ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    /**
     * @return Server\Servers
     */
    public function getAllServers(): Server\Servers
    {
        return $this->serverRepository->findAll();
    }

    /**
     * @param Commands\GetServersCommand $command
     * @return Server\Servers
     */
    public function getServers(Commands\GetServersCommand $command): Server\Servers
    {
        return $this->serverRepository->findAllByPage($command->getPage(), $command->getPerPage());
    }

    /**
     * @param Commands\GetServerCommand $command
     * @return Server\Server
     */
    public function getServer(Commands\GetServerCommand $command): Server\Server
    {
        $id = new Server\ServerId($command->getId());
        return $this->getNonNullServer($id);
    }

    /**
     * @param Commands\CreateServerCommand $command
     * @return void
     */
    public function createServer(Commands\CreateServerCommand $command): void
    {
        $server = Server\Server::of(
            $this->serverRepository->nextId()->value(),
            $command->getName(),
            $command->getDescription(),
            $command->getBody()
        );
        $this->serverRepository->save($server);
    }

    /**
     * @param Commands\UpdateServerCommand $command
     * @return void
     */
    public function updateServer(Commands\UpdateServerCommand $command): void
    {
        $id = new Server\ServerId($command->getId());
        $name = new Server\ServerName($command->getName());
        $description = new Server\ServerDescription($command->getDescription());
        $body = new Server\ServerBody($command->getBody());
        $server = $this->getNonNullServer($id)
            ->changeName($name)
            ->changeDescription($description)
            ->changeBody($body);
        $this->serverRepository->save($server);
    }

    /**
     * @param Commands\DeleteServerCommand $command
     * @return void
     */
    public function deleteServer(Commands\DeleteServerCommand $command): void
    {
        $id = new Server\ServerId($command->getId());
        $server = $this->getNonNullServer($id);
        $this->serverRepository->remove($server);
    }

    /**
     * @param Server\ServerId $id
     * @return Server\Server
     * @throws InvalidArgumentException
     */
    private function getNonNullServer(Server\ServerId $id): Server\Server
    {
        $server = $this->serverRepository->findById($id);
        if (is_null($server)) {
            throw new InvalidArgumentException(
                'Server does not exists.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $server;
    }
}
