<?php

namespace Tests\Feature\app\Http\Controllers;

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
use Tests\Helpers\Factory;
use Tests\TestCase;

class ServersControllerTest extends TestCase
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

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $servers = Factory::buildList(Server::class, [
            ['id' => 1, 'name' => 'Server 1', 'description' => '', 'body' => '', 'created_at' => new Carbon(), 'updated_at' => new Carbon()],
            ['id' => 2, 'name' => 'Server 2', 'description' => '', 'body' => '', 'created_at' => new Carbon(), 'updated_at' => new Carbon()],
            ['id' => 3, 'name' => 'Server 3', 'description' => '', 'body' => '', 'created_at' => new Carbon(), 'updated_at' => new Carbon()],
        ]);

        $perPage = 10;

        $this->mockServerRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($servers, $perPage));

        $response = $this->get('servers');

        $response->assertStatus(200);
        $response->assertViewHas('servers');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $response = $this->get('servers/create');

        $response->assertStatus(200);
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->mockServerForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $response = $this->post('servers');

        $response->assertRedirect('servers');
    }

    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
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

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $server = Factory::build(Server::class, [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $response = $this->get('servers/1');

        $response->assertStatus(200);
        $response->assertViewHas('server');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('servers/1');

        $response->assertStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $server = Factory::build(Server::class, [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $response = $this->get('servers/1/edit');

        $response->assertStatus(200);
        $response->assertViewHas('server');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('servers/1/edit');

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $server = Factory::build(Server::class, [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

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

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $server = Factory::build(Server::class, [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
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

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('servers/1');

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $server = Factory::build(Server::class, [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

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

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->delete('servers/1');

        $response->assertStatus(404);
    }
}
