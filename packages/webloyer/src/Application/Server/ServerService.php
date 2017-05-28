<?php

namespace Ngmy\Webloyer\Webloyer\Application\Server;

use DB;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerRepositoryInterface;

class ServerService
{
    private $serverRepository;

    public function __construct(ServerRepositoryInterface $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    public function getServerOfId($id)
    {
        return $this->serverRepository->serverOfId(new ServerId($id));
    }

    public function getAllServers()
    {
        return $this->serverRepository->allServers();
    }

    public function getServersOfPage($page = 1, $perPage = 10)
    {
        return $this->serverRepository->serversOfPage($page, $perPage);
    }

    public function saveServer($id, $name, $description, $body, $concurrencyVersion)
    {
        $server = DB::transaction(function () use ($id, $name, $description, $body, $concurrencyVersion) {
            if (!is_null($id)) {
                $existsServer = $this->getServerOfId($id);

                if (!is_null($existsServer)) {
                    $existsServer->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }

            $server = new Server(
                new ServerId($id),
                $name,
                $description,
                $body,
                null,
                null
            );
            return $this->serverRepository->save($server);
        });
        return $server;
    }

    public function removeServer($id)
    {
        return $this->serverRepository->remove($this->getServerOfId($id));
    }
}
