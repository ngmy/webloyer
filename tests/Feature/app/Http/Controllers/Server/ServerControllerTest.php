<?php

declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\Server;

use App\Http\Middleware\ApplySettings;
use App\Models\Server;
use App\Models\User;
use App\Repositories\Server\ServerInterface;
use App\Services\Form\Server\ServerForm;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\TestCase;

class ServerControllerTest extends TestCase
{
    use ControllerTestHelper;

    protected $mockServerRepository;

    protected $mockServerForm;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->partialMock(User::class);
        $user->shouldReceive('hasPermission')
            ->andReturn(true);
        $this->auth($user);

        $this->mockServerRepository = $this->mock(ServerInterface::class);
        $this->mockServerForm = $this->mock(ServerForm::class);
    }

    public function testShouldDisplayIndexPageWhenIndexPageIsRequested()
    {
        $i = 1;
        $servers = factory(Server::class, 3)->make()->each(function (Server $server) use ($i) {
            $server->id = $i++;
        });

        $perPage = 10;

        $this->mockServerRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($servers, $perPage));

        $response = $this->get('servers');

        $response->assertStatus(200);
        $response->assertViewHas('servers');
    }

    public function testShouldDisplayCreatePageWhenCreatePageIsRequested()
    {
        $response = $this->get('servers/create');

        $response->assertStatus(200);
    }

    public function testShouldRedirectToIndexPageWhenStoreProcessSucceeds()
    {
        $this->mockServerForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $response = $this->post('servers');

        $response->assertRedirect('servers');
    }

    public function testShouldRedirectToCreatePageWhenStoreProcessFails()
    {
        $this->mockServerForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockServerForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->post('servers');

        $response->assertRedirect('servers/create');
        $response->assertSessionHasErrors();
    }

    public function testShouldDisplayShowPageWhenShowPageIsRequestedAndResourceIsFound()
    {
        $server = factory(Server::class)->make([
            'id' => 1,
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $response = $this->get('servers/1');

        $response->assertStatus(200);
        $response->assertViewHas('server');
    }

    public function testShouldDisplayNotFoundPageWhenShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('servers/1');

        $response->assertStatus(404);
    }

    public function testShouldDisplayEditPageWhenEditPageIsRequestedAndResourceIsFound()
    {
        $server = factory(Server::class)->make([
            'id' => 1,
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $response = $this->get('servers/1/edit');

        $response->assertStatus(200);
        $response->assertViewHas('server');
    }

    public function testShouldDisplayNotFoundPageWhenEditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('servers/1/edit');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenUpdateProcessSucceeds()
    {
        $server = factory(Server::class)->make();

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->mockServerForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $response = $this->put('servers/1');

        $response->assertRedirect('servers');
    }

    public function testShouldRedirectToEditPageWhenUpdateProcessFails()
    {
        $server = factory(Server::class)->make([
            'id' => 1,
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->mockServerForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $this->mockServerForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->put('servers/1');

        $response->assertRedirect('servers/1/edit');
        $response->assertSessionHasErrors();
    }

    public function testShouldDisplayNotFoundPageWhenUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('servers/1');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenDestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $server = factory(Server::class)->make();

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->mockServerRepository
            ->shouldReceive('delete')
            ->once();

        $response = $this->delete('servers/1');

        $response->assertRedirect('servers');
    }

    public function testShouldDisplayNotFoundPageWhenDestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->delete('servers/1');

        $response->assertStatus(404);
    }
}
