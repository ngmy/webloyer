<?php

namespace Tests\Feature\app\Http\Controllers;

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
use Tests\Helpers\Factory;
use Tests\TestCase;

class UsersControllerTest extends TestCase
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
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockUserRepository = $this->mock(UserInterface::class);
        $this->mockRoleRepsitory = $this->mock(RoleInterface::class);
        $this->mockUserForm = $this->mock(UserForm::class);
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $users = Factory::buildList(User::class, [
            ['id' => 1, 'name' => 'User 1', 'email' => 'user1@example.com', 'password' => '12345678', 'created_at' => new Carbon(), 'updated_at' => new Carbon()],
            ['id' => 2, 'name' => 'User 2', 'email' => 'user2@example.com', 'password' => '12345678', 'created_at' => new Carbon(), 'updated_at' => new Carbon()],
            ['id' => 3, 'name' => 'User 3', 'email' => 'user3@example.com', 'password' => '12345678', 'created_at' => new Carbon(), 'updated_at' => new Carbon()],
        ]);

        $perPage = 10;

        $this->mockUserRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($users, $perPage));

        $response = $this->get('users');

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $this->mockRoleRepsitory
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $response = $this->get('users/create');

        $response->assertStatus(200);
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->mockUserForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $response = $this->post('users');

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
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

    public function test_Should_RedirectToEditPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build(User::class, [
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

        $response = $this->get('users/1');

        $response->assertRedirect('users/1/edit');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1');

        $response->assertStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build(User::class, [
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

        $response = $this->get('users/1/edit');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1/edit');

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $user = Factory::build(User::class, [
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
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $response = $this->put('users/1');

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $user = Factory::build(User::class, [
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

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('users/1');

        $response->assertStatus(404);
    }

    public function test_Should_DisplayPasswordChangePage_When_PasswordChangePageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build(User::class, [
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

        $response = $this->get('users/1/password/change');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_PasswordChangePageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1/password/change');

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_PasswordUpdateProcessSucceeds()
    {
        $user = Factory::build(User::class, [
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
            ->shouldReceive('updatePassword')
            ->once()
            ->andReturn(true);

        $response = $this->put('users/1/password');

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToPasswordChangePage_When_PasswordUpdateProcessFails()
    {
        $user = Factory::build(User::class, [
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

    public function test_Should_DisplayNotFoundPage_When_PasswordUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('users/1/password');

        $response->assertStatus(404);
    }

    public function test_Should_DisplayEditRolePage_When_EditRolePageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build(User::class, [
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

        $this->mockRoleRepsitory
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $response = $this->get('users/1/role/edit');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_EditRolePageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('users/1/role/edit');

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_RoleUpdateProcessSucceeds()
    {
        $user = Factory::build(User::class, [
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
            ->andReturn(true);

        $response = $this->put('users/1/role');

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToEditRolePage_When_EditUpdateProcessFails()
    {
        $user = Factory::build(User::class, [
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

    public function test_Should_DisplayNotFoundPage_When_RoleUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('users/1/role');

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $user = Factory::build(User::class, [
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

        $this->mockUserRepository
            ->shouldReceive('delete')
            ->once();

        $response = $this->delete('users/1');

        $response->assertRedirect('users');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->delete('users/1');

        $response->assertStatus(404);
    }
}
