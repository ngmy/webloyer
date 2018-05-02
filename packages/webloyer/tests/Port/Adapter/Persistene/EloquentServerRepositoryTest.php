<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentServerRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Server as EloquentServer;
use Tests\Helpers\EloquentFactory;
use TestCase;

class EloquentServerRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetServerOfId()
    {
        $server = $this->createServer([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'name'        => $server->name(),
            'description' => $server->description(),
            'body'        => $server->body(),
            'created_at'  => $server->createdAt(),
            'updated_at'  => $server->updatedAt(),
        ]);

        $eloquentServerRepository = $this->createEloquentServerRepository();
        $expectedResult = $eloquentServerRepository->toEntity($createdEloquentServer);

        $actualResult = $eloquentServerRepository->serverOfId($expectedResult->serverId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllServers()
    {
        $servers = [
            $this->createServer([
                'name'      => 'Server 1',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 2',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 3',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 4',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 5',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
        ];
        $page = 1;
        $limit = 10;

        $createdEloquentServers = EloquentFactory::createList(EloquentServer::class, array_map(function ($server) {
            return [
                'name'        => $server->name(),
                'description' => $server->description(),
                'body'        => $server->body(),
                'created_at'  => $server->createdAt(),
                'updated_at'  => $server->updatedAt(),
            ];
        }, $servers));

        $eloquentServerRepository = $this->createEloquentServerRepository();

        $expectedResult = (new Collection(array_map(function ($eloquentServer) use ($eloquentServerRepository) {
                return $eloquentServerRepository->toEntity($eloquentServer);
            }, $createdEloquentServers)))->all();

        $actualResult = $eloquentServerRepository->allServers();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetServersOfPage()
    {
        $servers = [
            $this->createServer([
                'name'      => 'Server 1',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 2',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 3',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 4',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createServer([
                'name'      => 'Server 5',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
        ];
        $page = 1;
        $limit = 10;

        $createdEloquentServers = EloquentFactory::createList(EloquentServer::class, array_map(function ($server) {
            return [
                'name'        => $server->name(),
                'description' => $server->description(),
                'body'        => $server->body(),
                'created_at'  => $server->createdAt(),
                'updated_at'  => $server->updatedAt(),
            ];
        }, $servers));

        $eloquentServerRepository = $this->createEloquentServerRepository();

        $createdServers = new Collection(array_map(function ($eloquentServer) use ($eloquentServerRepository) {
                return $eloquentServerRepository->toEntity($eloquentServer);
            }, $createdEloquentServers));

        $expectedResult = new LengthAwarePaginator(
            $createdServers,
            $createdServers->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );

        $actualResult = $eloquentServerRepository->serversOfPage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewServer()
    {
        $newServer = $this->createServer([
            'name' => 'some name',
            'description' => 'some desctiption.',
            'body' => 'some body.',
        ]);
        $eloquentServerRepository = $this->createEloquentServerRepository();

        $returnedServer = $eloquentServerRepository->save($newServer);

        $createdEloquentServer = EloquentServer::find($returnedServer->ServerId()->id());

        $this->assertEquals($newServer->name(), $createdEloquentServer->name);
        $this->assertEquals($newServer->description(), $createdEloquentServer->description);
        $this->assertEquals($newServer->body(), $createdEloquentServer->body);

        $this->assertEquals($newServer->name(), $returnedServer->name());
        $this->assertEquals($newServer->description(), $returnedServer->description());
        $this->assertEquals($newServer->body(), $returnedServer->body());

        $this->assertEquals($createdEloquentServer->created_at, $returnedServer->createdAt());
        $this->assertEquals($createdEloquentServer->updated_at, $returnedServer->createdAt());
    }

    public function test_Should_UpdateExistingServer()
    {
        $eloquentServerShouldBeUpdated = EloquentFactory::create(EloquentServer::class, [
            'name'        => 'some name 1',
            'description' => 'some description 1',
            'body'        => 'some body 1',
        ]);

        $eloquentServerRepository = $this->createEloquentServerRepository();

        $newServer = $this->createServer([
            'serverId' => $eloquentServerShouldBeUpdated->id,
            'name'        => 'some name 2',
            'description' => 'some description 2',
            'body'        => 'some body 2',
        ]);

        $returnedServer = $eloquentServerRepository->save($newServer);

        $updatedEloquentServer = EloquentServer::find($eloquentServerShouldBeUpdated->id);

        $this->assertEquals($newServer->name(), $updatedEloquentServer->name);
        $this->assertEquals($newServer->description(), $updatedEloquentServer->description);
        $this->assertEquals($newServer->body(), $updatedEloquentServer->body);

        $this->assertEquals($newServer->name(), $returnedServer->name());
        $this->assertEquals($newServer->description(), $returnedServer->description());
        $this->assertEquals($newServer->body(), $returnedServer->body());

        $this->assertEquals($updatedEloquentServer->created_at, $returnedServer->createdAt());
        $this->assertEquals($updatedEloquentServer->updated_at, $returnedServer->createdAt());
    }

    public function test_Should_DeleteExistingServer()
    {
        $eloquentServerShouldBeDeleted = EloquentFactory::create(EloquentServer::class, [
            'name'        => 'some name',
            'description' => 'some description',
            'body'        => 'some body',
        ]);

        $eloquentServerRepository = $this->createEloquentServerRepository();

        $eloquentServerRepository->remove($eloquentServerRepository->toEntity($eloquentServerShouldBeDeleted));

        $deletedEloquentServer = EloquentServer::find($eloquentServerShouldBeDeleted->id);

        $this->assertNull($deletedEloquentServer);
    }

    private function createServer(array $params = [])
    {
        $serverId = null;
        $name = '';
        $description = '';
        $body = '';
        $createdAt = null;
        $updatedAt = null;

        extract($params);

        return new Server(
            new ServerId($serverId),
            $name,
            $description,
            $body,
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }

    private function createEloquentServerRepository(array $params = [])
    {
        extract($params);

        return new EloquentServerRepository(new EloquentServer());
    }
}
