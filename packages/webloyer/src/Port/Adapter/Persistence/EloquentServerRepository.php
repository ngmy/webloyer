<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Server as EloquentServer;

class EloquentServerRepository implements ServerRepositoryInterface
{
    private $eloquentServer;

    /**
     * Create a new repository instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Server $eloquentServer
     * @return void
     */
    public function __construct(EloquentServer $eloquentServer)
    {
        $this->eloquentServer = $eloquentServer;
    }

    public function allServers()
    {
        $eloquentServers = $this->eloquentServer->all();

        $servers = $eloquentServers->map(function ($eloquentServer, $key) {
            return $eloquentServer->toEntity();
        })->all();

        return $servers;
    }

    public function serversOfPage($page = 1, $limit = 10)
    {
        $eloquentServers = $this->eloquentServer
            ->orderBy('name')
            ->get();

        $servers = $eloquentServers
            ->slice($limit * ($page - 1), $limit)
            ->map(function ($eloquentServer, $key) {
                return $eloquentServer->toEntity();
            });

        return new LengthAwarePaginator(
            $servers,
            $servers->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );
    }

    public function serverOfId(ServerId $serverId)
    {
        $primaryKey = $serverId->id();

        $eloquentServer = $this->eloquentServer->find($primaryKey);

        $server = $eloquentServer->toEntity();

        return $server;
    }

    public function remove(Server $server)
    {
        $eloquentServer = $this->toEloquent($server);

        $eloquentServer->delete();

        return true;
    }

    public function save(Server $server)
    {
        $eloquentServer = $this->toEloquent($server);

        $eloquentServer->save();

        $server = $eloquentServer->toEntity();

        return $server;
    }

    public function toEloquent(Server $server)
    {
        $primaryKey = $server->serverId()->id();

        if (is_null($primaryKey)) {
            $eloquentServer = new EloquentServer();
        } else {
            $eloquentServer = $this->eloquentServer->find($primaryKey);
        }

        $eloquentServer->name = $server->name();
        $eloquentServer->description = $server->description();
        $eloquentServer->body = $server->body();

        return $eloquentServer;
    }
}
