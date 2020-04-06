<?php

namespace Tests\Unit\app\Repositories\Server;

use App\Models\Server;
use App\Repositories\Server\EloquentServer;
use Tests\TestCase;

class EloquentServerTest extends TestCase
{
    protected $useDatabase = true;

    /** @var EloquentServer */
    private $sut;

    public function test_Should_GetServerById()
    {
        $server = factory(Server::class)->create();

        $actual = $this->sut->byId($server->id);

        $this->assertTrue($server->is($actual));
    }

    public function test_Should_GetServersByPage()
    {
        $servers = factory(Server::class, 5)->create();

        $actual = $this->sut->byPage();

        $this->assertCount(5, $actual->items());
    }

    public function test_Should_CreateNewServer()
    {
        $actual = $this->sut->create([
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
        ]);

        $this->assertDatabaseHas('servers', $actual->toArray());
    }

    public function test_Should_UpdateExistingServer()
    {
        $server = factory(Server::class)->create();

        $this->sut->update([
            'id'          => $server->id,
            'name'        => 'Server 2',
            'description' => 'Description',
            'body'        => '<?php $x = 1;',
        ]);

        $this->assertDatabaseHas('servers', [
            'id'          => $server->id,
            'name'        => 'Server 2',
            'description' => 'Description',
            'body'        => '<?php $x = 1;',
        ]);
    }

    public function test_Should_DeleteExistingServer()
    {
        $server = factory(Server::class)->create();

        $this->sut->delete($server->id);

        $this->assertDatabaseMissing('servers', ['id' => $server->id]);
    }

    /**
     * @before
     */
    public function setUpSut(): void
    {
        $this->sut = new EloquentServer(new Server());
    }
}
