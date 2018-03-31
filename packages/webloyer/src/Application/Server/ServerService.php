<?php

namespace Ngmy\Webloyer\Webloyer\Application\Server;

use DB;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerRepositoryInterface;

class ServerService
{
    private $serverRepository;

    /**
     * Create a new application service instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerRepositoryInterface $serverRepository
     * @return void
     */
    public function __construct(ServerRepositoryInterface $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    /**
     * Get all servers.
     *
     * @return array
     */
    public function getAllServers()
    {
        return $this->serverRepository->allServers();
    }

    /**
     * Get servers by page.
     *
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getServersByPage($page = 1, $perPage = 10)
    {
        return $this->serverRepository->serversOfPage($page, $perPage);
    }

    /**
     * Get a server by id.
     *
     * @param int $serverId
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server
     */
    public function getServerById($serverId)
    {
        return $this->serverRepository->serverOfId(new ServerId($serverId));
    }

    /**
     * Create or Update a server.
     *
     * @param int|null $serverId
     * @param string   $name
     * @param string   $description
     * @param string   $body
     * @param string   $concurrencyVersion
     * @return void
     */
    public function saveServer($serverId, $name, $description, $body, $concurrencyVersion)
    {
        DB::transaction(function () use ($serverId, $name, $description, $body, $concurrencyVersion) {
            if (!is_null($serverId)) {
                $existsServer = $this->getServerById($serverId);

                if (!is_null($existsServer)) {
                    $existsServer->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }
            $server = new Server(
                new ServerId($serverId),
                $name,
                $description,
                $body,
                null,
                null
            );
            $this->serverRepository->save($server);
        });
    }

    /**
     * Remove a server.
     *
     * @param int $serverId
     * @return void
     */
    public function removeServer($serverId)
    {
        $this->serverRepository->remove($this->getServerById($serverId));
    }
}
