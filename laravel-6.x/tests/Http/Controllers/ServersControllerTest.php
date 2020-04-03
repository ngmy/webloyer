<?php

use Tests\Helpers\Factory;
use Tests\Helpers\DummyMiddleware;

class ServersControllerTest extends TestCase
{
    use Tests\Helpers\ControllerTestHelper;

    use Tests\Helpers\MockeryHelper;

    protected $mockServerRepository;

    protected $mockServerForm;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(\App\Http\Middleware\ApplySettings::class, new DummyMiddleware);

        Session::start();

        $user = $this->mockPartial('App\Models\User');
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockServerRepository = $this->mock('App\Repositories\Server\ServerInterface');
        $this->mockServerForm = $this->mock('App\Services\Form\Server\ServerForm');
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $servers = Factory::buildList('App\Models\Server', [
            ['id' => 1, 'name' => 'Server 1', 'description' => '', 'body' => '', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
            ['id' => 2, 'name' => 'Server 2', 'description' => '', 'body' => '', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
            ['id' => 3, 'name' => 'Server 3', 'description' => '', 'body' => '', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
        ]);

        $perPage = 10;

        $this->mockServerRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Illuminate\Pagination\Paginator($servers, $perPage));

        $this->get('servers');

        $this->assertResponseOk();
        $this->assertViewHas('servers');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $this->get('servers/create');

        $this->assertResponseOk();
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->mockServerForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->post('servers');

        $this->assertRedirectedToRoute('servers.index');
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

        $this->post('servers');

        $this->assertRedirectedToRoute('servers.create');
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $server = Factory::build('App\Models\Server', [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->get('servers/1');

        $this->assertResponseOk();
        $this->assertViewHas('server');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('servers/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $server = Factory::build('App\Models\Server', [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->get('servers/1/edit');

        $this->assertResponseOk();
        $this->assertViewHas('server');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('servers/1/edit');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $server = Factory::build('App\Models\Server', [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->mockServerForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $this->put('servers/1');

        $this->assertRedirectedToRoute('servers.index');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $server = Factory::build('App\Models\Server', [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
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

        $this->put('servers/1');

        $this->assertRedirectedToRoute('servers.edit', [$server]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->put('servers/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $server = Factory::build('App\Models\Server', [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->mockServerRepository
            ->shouldReceive('delete')
            ->once();

        $this->delete('servers/1');

        $this->assertRedirectedToRoute('servers.index');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->delete('servers/1');

        $this->assertResponseStatus(404);
    }
}
