<?php

namespace Tests\Unit\app\Repositories\Server;

use App\Models\Server;
use App\Repositories\Server\EloquentServer;
use Tests\Helpers\Factory;
use Tests\TestCase;

class EloquentServerTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetServerById()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        $serverRepository = new EloquentServer(new Server());

        $foundServer = $serverRepository->byId($arrangedServer->id);

        $this->assertEquals('Server 1', $foundServer->name);
        $this->assertEquals('', $foundServer->description);
        $this->assertEquals('', $foundServer->body);
    }

    public function test_Should_GetServersByPage()
    {
        Factory::createList(Server::class, [
            ['name' => 'Server 1', 'description' => '', 'body' => ''],
            ['name' => 'Server 2', 'description' => '', 'body' => ''],
            ['name' => 'Server 3', 'description' => '', 'body' => ''],
            ['name' => 'Server 4', 'description' => '', 'body' => ''],
            ['name' => 'Server 5', 'description' => '', 'body' => ''],
        ]);

        $serverRepository = new EloquentServer(new Server());

        $foundServers = $serverRepository->byPage();

        $this->assertCount(5, $foundServers->items());
    }

    public function test_Should_CreateNewServer()
    {
        $serverRepository = new EloquentServer(new Server());

        $returnedServer = $serverRepository->create([
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        $server = new Server();
        $createdServer = $server->find($returnedServer->id);

        $this->assertEquals('Server 1', $createdServer->name);
        $this->assertEquals('', $createdServer->description);
        $this->assertEquals('', $createdServer->body);
    }

    public function test_Should_UpdateExistingServer()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        $serverRepository = new EloquentServer(new Server());

        $serverRepository->update([
            'id'          => $arrangedServer->id,
            'name'        => 'Server 2',
            'description' => 'Description',
            'body'        => '<?php $x = 1;',
        ]);

        $server = new Server();
        $updatedServer = $server->find($arrangedServer->id);

        $this->assertEquals('Server 2', $updatedServer->name);
        $this->assertEquals('Description', $updatedServer->description);
        $this->assertEquals('<?php $x = 1;', $updatedServer->body);
    }

    public function test_Should_DeleteExistingServer()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        $serverRepository = new EloquentServer(new Server());

        $serverRepository->delete($arrangedServer->id);

        $server = new Server();
        $deletedServer = $server->find($arrangedServer->id);

        $this->assertNull($deletedServer);
    }
}
