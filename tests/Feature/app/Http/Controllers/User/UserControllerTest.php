<?php

declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User;

use App\Http\Middleware\ApplySettings;
use App\Models\User;
use App\Repositories\Role\RoleInterface;
use App\Repositories\User\UserInterface;
use App\Services\Form\User\UserForm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use ControllerTestHelper;

    protected $mockUserRepository;

    protected $mockRoleRepository;

    protected $mockUserForm;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->partialMock(User::class);
        $user->shouldReceive('hasPermission')
            ->andReturn(true);
        $this->auth($user);

        $this->mockUserRepository = $this->mock(UserInterface::class);
        $this->mockRoleRepsitory = $this->mock(RoleInterface::class);
        $this->mockUserForm = $this->mock(UserForm::class);
    }

    public function testShouldDisplayIndexPageWhenIndexPageIsRequested()
    {
        $i = 1;
        $users = factory(User::class, 3)->make()->each(function (User $user) use ($i) {
            $user->id = $i++;
        });

        $perPage = 10;

        $this->mockUserRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($users, $perPage));

        $response = $this->get('users');

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function testShouldDisplayCreatePageWhenCreatePageIsRequested()
    {
        $this->mockRoleRepsitory
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $response = $this->get('users/create');

        $response->assertStatus(200);
    }

    public function testShouldRedirectToIndexPageWhenStoreProcessSucceeds()
    {
        $this->mockUserForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $response = $this->post('users');

        $response->assertRedirect('users');
    }

    public function testShouldRedirectToCreatePageWhenStoreProcessFails()
    {
        $this->mockUserForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockUserForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->post('users');

        $response->assertRedirect('users/create');
        $response->assertSessionHasErrors();
    }

    public function testShouldRedirectToEditPageWhenShowPageIsRequestedAndResourceIsFound()
    {
        $user = factory(User::class)->make([
            'id' => 1,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $response = $this->get('users/1');

        $response->assertRedirect('users/1/edit');
    }

    public function testShouldDisplayNotFoundPageWhenShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1');

        $response->assertStatus(404);
    }

    public function testShouldDisplayEditPageWhenEditPageIsRequestedAndResourceIsFound()
    {
        $user = factory(User::class)->make([
            'id' => 1,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $response = $this->get('users/1/edit');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function testShouldDisplayNotFoundPageWhenEditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1/edit');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenUpdateProcessSucceeds()
    {
        $user = factory(User::class)->make();

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $response = $this->put('users/1');

        $response->assertRedirect('users');
    }

    public function testShouldRedirectToEditPageWhenUpdateProcessFails()
    {
        $user = factory(User::class)->make([
            'id' => 1,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $this->mockUserForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->put('users/1');

        $response->assertRedirect('users/1/edit');
        $response->assertSessionHasErrors();
    }

    public function testShouldDisplayNotFoundPageWhenUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('users/1');

        $response->assertStatus(404);
    }

    public function testShouldDisplayPasswordChangePageWhenPasswordChangePageIsRequestedAndResourceIsFound()
    {
        $user = factory(User::class)->make([
            'id' => 1,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $response = $this->get('users/1/password/change');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function testShouldDisplayNotFoundPageWhenPasswordChangePageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1/password/change');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenPasswordUpdateProcessSucceeds()
    {
        $user = factory(User::class)->make();

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('updatePassword')
            ->once()
            ->andReturn(true);

        $response = $this->put('users/1/password');

        $response->assertRedirect('users');
    }

    public function testShouldRedirectToPasswordChangePageWhenPasswordUpdateProcessFails()
    {
        $user = factory(User::class)->make([
            'id' => 1,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('updatePassword')
            ->once()
            ->andReturn(false);

        $this->mockUserForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->put('users/1/password');

        $response->assertRedirect('users/1/password/change');
        $response->assertSessionHasErrors();
    }

    public function testShouldDisplayNotFoundPageWhenPasswordUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('users/1/password');

        $response->assertStatus(404);
    }

    public function testShouldDisplayEditRolePageWhenEditRolePageIsRequestedAndResourceIsFound()
    {
        $user = factory(User::class)->make([
            'id' => 1,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockRoleRepsitory
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $response = $this->get('users/1/role/edit');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function testShouldDisplayNotFoundPageWhenEditRolePageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1/role/edit');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenRoleUpdateProcessSucceeds()
    {
        $user = factory(User::class)->make();

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('updateRole')
            ->once()
            ->andReturn(true);

        $response = $this->put('users/1/role');

        $response->assertRedirect('users');
    }

    public function testShouldRedirectToEditRolePageWhenEditUpdateProcessFails()
    {
        $user = factory(User::class)->make([
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon(),
            'updated_at' => new Carbon(),
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('updateRole')
            ->once()
            ->andReturn(false);

        $this->mockUserForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->put('users/1/role');

        $response->assertRedirect('users/1/role/edit');
        $response->assertSessionHasErrors();
    }

    public function testShouldDisplayNotFoundPageWhenRoleUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('users/1/role');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenDestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $user = factory(User::class)->make();

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserRepository
            ->shouldReceive('delete')
            ->once();

        $response = $this->delete('users/1');

        $response->assertRedirect('users');
    }

    public function testShouldDisplayNotFoundPageWhenDestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->delete('users/1');

        $response->assertStatus(404);
    }
}
