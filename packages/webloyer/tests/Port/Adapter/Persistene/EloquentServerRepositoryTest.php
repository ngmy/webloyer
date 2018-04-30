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

//    public function test_Should_CreateNewServer()
//    {
//        $eloquentServerRepository = new EloquentServer(new App\Models\Server);
//
//        $returnedServer = $eloquentServerRepository->create([
//            'name'        => 'Server 1',
//            'description' => '',
//            'body'        => '',
//        ]);
//
//        $server = new App\Models\Server;
//        $createdServer = $server->find($returnedServer->id);
//
//        $this->assertEquals('Server 1', $createdServer->name);
//        $this->assertEquals('', $createdServer->description);
//        $this->assertEquals('', $createdServer->body);
//    }
//
//    public function test_Should_UpdateExistingServer()
//    {
//        $arrangedServer = EloquentFactory::create(EloquentServer::class, [
//            'name'        => 'Server 1',
//            'description' => '',
//            'body'        => '',
//        ]);
//
//        $eloquentServerRepository = $this->createEloquentServerRepository();
//
//        $eloquentServerRepository->update([
//            'id'          => $arrangedServer->id,
//            'name'        => 'Server 2',
//            'description' => 'Description',
//            'body'        => '<?php $x = 1;',
//        ]);
//
//        $server = new App\Models\Server;
//        $updatedServer = $server->find($arrangedServer->id);
//
//        $this->assertEquals('Server 2', $updatedServer->name);
//        $this->assertEquals('Description', $updatedServer->description);
//        $this->assertEquals('<?php $x = 1;', $updatedServer->body);
//    }
//
//    public function test_Should_DeleteExistingServer()
//    {
//        $arrangedServer = EloquentFactory::create(EloquentServer::class, [
//            'name'        => 'Server 1',
//            'description' => '',
//            'body'        => '',
//        ]);
//
//        $eloquentServerRepository = $this->createEloquentServerRepository();
//
//        $eloquentServerRepository->delete($arrangedServer->id);
//
//        $server = new App\Models\Server;
//        $deletedServer = $server->find($arrangedServer->id);
//
//        $this->assertNull($deletedServer);
//    }

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
