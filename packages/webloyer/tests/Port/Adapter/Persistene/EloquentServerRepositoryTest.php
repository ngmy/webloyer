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
        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'created_at' => '2018-04-30 12:00:00',
            'updated_at' => '2018-04-30 12:00:00',
        ]);
        $expectedResult = $createdEloquentServer->toEntity();

        $actualResult = $this->createEloquentServerRepository()->serverOfId($expectedResult->serverId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllServers()
    {
        $createdEloquentServers = EloquentFactory::createList(EloquentServer::class, [
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $expectedResult = (new Collection(array_map(function ($eloquentServer) {
            return $eloquentServer->toEntity();
        }, $createdEloquentServers)))->all();

        $actualResult = $this->createEloquentServerRepository()->allServers();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetServersOfPage()
    {
        $createdEloquentServers = EloquentFactory::createList(EloquentServer::class, [
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $createdServers = new Collection(array_map(function ($eloquentServer) {
            return $eloquentServer->toEntity();
        }, $createdEloquentServers));
        $page = 1;
        $limit = 10;
        $expectedResult = new LengthAwarePaginator(
            $createdServers,
            $createdServers->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );

        $actualResult = $this->createEloquentServerRepository()->serversOfPage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewServer()
    {
        $newServer = $this->createServer();

        $returnedServer = $this->createEloquentServerRepository()->save($newServer);

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
        $eloquentServerShouldBeUpdated = EloquentFactory::create(EloquentServer::class);

        $newServer = $this->createServer([
            'serverId'    => $eloquentServerShouldBeUpdated->id,
            'name'        => 'new name',
            'description' => 'new description',
            'body'        => 'new body',
        ]);

        $returnedServer = $this->createEloquentServerRepository()->save($newServer);

        $updatedEloquentServer = EloquentServer::find($eloquentServerShouldBeUpdated->id);

        $this->assertEquals($newServer->name(), $updatedEloquentServer->name);
        $this->assertEquals($newServer->description(), $updatedEloquentServer->description);
        $this->assertEquals($newServer->body(), $updatedEloquentServer->body);

        $this->assertEquals($newServer->name(), $returnedServer->name());
        $this->assertEquals($newServer->description(), $returnedServer->description());
        $this->assertEquals($newServer->body(), $returnedServer->body());

        $this->assertEquals($updatedEloquentServer->created_at, $returnedServer->createdAt());
        $this->assertEquals($updatedEloquentServer->updated_at, $returnedServer->updatedAt());
    }

    public function test_Should_DeleteExistingServer()
    {
        $eloquentServerShouldBeDeleted = EloquentFactory::create(EloquentServer::class);

        $this->createEloquentServerRepository()->remove($eloquentServerShouldBeDeleted->toEntity());

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
