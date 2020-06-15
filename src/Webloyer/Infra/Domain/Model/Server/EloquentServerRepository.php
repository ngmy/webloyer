<?php

declare(strict_types=1);

namespace Webloyer\Infra\Domain\Model\Server;

use Common\Domain\Model\Identity\IdGenerator;
use Webloyer\Domain\Model\Server\{
    Server,
    ServerId,
    ServerRepository,
    Servers,
};
use Webloyer\Infra\Persistence\Eloquent\Models\Server as ServerOrm;

class EloquentServerRepository implements ServerRepository
{
    /** @var IdGenerator */
    private $idGenerator;

    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    /**
     * @return ServerId
     * @see ServerRepository::nextId()
     */
    public function nextId(): ServerId
    {
        return new ServerId($this->idGenerator->generate());
    }

    /**
     * @return Servers
     * @see ServerRepository::findAll()
     */
    public function findAll(): Servers
    {
        $serverArray = ServerOrm::orderBy('name')
            ->get()
            ->map(function (ServerOrm $serverOrm): Server {
                return $serverOrm->toEntity();
            })
            ->toArray();
        return new Servers(...$serverArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Servers
     * @see ServerRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Servers
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $serverArray = ServerOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (ServerOrm $serverOrm): Server {
                return $serverOrm->toEntity();
            })
            ->toArray();
        return new Servers(...$serverArray);
    }

    /**
     * @param ServerId $id
     * @return Server|null
     * @see ServerRepository::findById()
     */
    public function findById(ServerId $id): ?Server
    {
        $serverOrm = ServerOrm::ofId($id->value())->first();
        if (is_null($serverOrm)) {
            return null;
        }
        return $serverOrm->toEntity();
    }

    /**
     * @param Server $server
     * @return void
     * @see ServerRepository::remove()
     */
    public function remove(Server $server): void
    {
        $serverOrm = ServerOrm::ofId($server->id())->first();
        if (is_null($serverOrm)) {
            return;
        }
        $serverOrm->delete();
    }

    /**
     * @param Server $server
     * @return void
     * @see ServerRepository::save()
     */
    public function save(Server $server): void
    {
        $serverOrm = ServerOrm::firstOrNew(['uuid' => $server->id()]);
        $server->provide($serverOrm);
        $serverOrm->save();

        $server->setSurrogateId($serverOrm->id)
            ->setCreatedAt($serverOrm->created_at)
            ->setUpdatedAt($serverOrm->updated_at);
    }
}
