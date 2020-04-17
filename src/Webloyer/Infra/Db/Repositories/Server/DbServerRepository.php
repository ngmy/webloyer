<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Repositories\Server;

use Str;
use Webloyer\Domain\Model\Server;
use Webloyer\Infra\Db\Eloquents\Server\Server as ServerOrm;

class DbServerRepository implements Server\ServerRepository
{
    /**
     * @return Server\ServerId
     * @see Server\ServerRepository::nextId()
     */
    public function nextId(): Server\ServerId
    {
        return new Server\ServerId(Str::orderedUuid()->toString());
    }

    /**
     * @return Server\Servers
     * @see Server\ServerRepository::findAll()
     */
    public function findAll(): Server\Servers
    {
        $serverArray = ServerOrm::orderBy('name')
            ->all()
            ->map(function (ServerOrm $serverOrm): Server\Server {
                return $serverOrm->toEntity();
            })
            ->toArray();
        return new Server\Servers(...$serverArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Server\Servers
     * @see Server\ServerRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Server\Servers
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $serverArray = ServerOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (ServerOrm $serverOrm): Server\Server {
                return $serverOrm->toEntity();
            })
            ->toArray();
        return new Server\Servers(...$serverArray);
    }

    /**
     * @param Server\ServerId $id
     * @return Server\Server|null
     * @see Server\ServerRepository::findById()
     */
    public function findById(Server\ServerId $id): ?Server\Server
    {
        $serverOrm = ServerOrm::ofId($id->value())->first();
        if (is_null($serverOrm)) {
            return null;
        }
        return $serverOrm->toEntity();
    }

    /**
     * @param Server\Server $server
     * @return void
     * @see Server\ServerRepository::remove()
     */
    public function remove(Server\Server $server): void
    {
        $serverOrm = ServerOrm::ofId($server->id())->first();
        if (is_null($serverOrm)) {
            return;
        }
        $serverOrm->delete();
    }

    /**
     * @param Server\Server $server
     * @return void
     * @see Server\ServerRepository::save()
     */
    public function save(Server\Server $server): void
    {
        $serverOrm = ServerOrm::firstOrNew(['uuid' => $server->id()]);
        $server->provide($serverOrm);
        $serverOrm->save();
    }
}
