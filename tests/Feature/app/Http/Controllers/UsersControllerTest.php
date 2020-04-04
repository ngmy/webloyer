<?php

namespace Tests\Feature\app\Http\Controllers;

use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\Factory;
use Tests\Helpers\MockeryHelper;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    protected $mockUserRepository;

    protected $mockRoleRepository;

    protected $mockUserForm;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(\App\Http\Middleware\ApplySettings::class, new DummyMiddleware);

        Session::start();

        $user = $this->mockPartial('App\Models\User');
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockUserRepository = $this->mock('App\Repositories\User\UserInterface');
        $this->mockRoleRepsitory = $this->mock('App\Repositories\Role\RoleInterface');
        $this->mockUserForm = $this->mock('App\Services\Form\User\UserForm');
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $users = Factory::buildList('App\Models\User', [
            ['id' => 1, 'name' => 'User 1', 'email' => 'user1@example.com', 'password' => '12345678', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
            ['id' => 2, 'name' => 'User 2', 'email' => 'user2@example.com', 'password' => '12345678', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
            ['id' => 3, 'name' => 'User 3', 'email' => 'user3@example.com', 'password' => '12345678', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
        ]);

        $perPage = 10;

        $this->mockUserRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Illuminate\Pagination\Paginator($users, $perPage));

        $this->get('users');

        $this->assertResponseOk();
        $this->assertViewHas('users');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $this->mockRoleRepsitory
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

        $this->get('users/create');

        $this->assertResponseOk();
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->mockUserForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->post('users');

        $this->assertRedirectedToRoute('users.index');
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

        $this->post('users');

        $this->assertRedirectedToRoute('users.create');
        $this->assertSessionHasErrors();
    }

    public function test_Should_RedirectToEditPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->get('users/1');

        $this->assertRedirectedToRoute('users.edit', [$user]);
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('users/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->get('users/1/edit');

        $this->assertResponseOk();
        $this->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('users/1/edit');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $this->put('users/1');

        $this->assertRedirectedToRoute('users.index');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
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

        $this->put('users/1');

        $this->assertRedirectedToRoute('users.edit', [$user]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->put('users/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayPasswordChangePage_When_PasswordChangePageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->get('users/1/password/change');

        $this->assertResponseOk();
        $this->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_PasswordChangePageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('users/1/password/change');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_PasswordUpdateProcessSucceeds()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('updatePassword')
            ->once()
            ->andReturn(true);

        $this->put('users/1/password');

        $this->assertRedirectedToRoute('users.index');
    }

    public function test_Should_RedirectToPasswordChangePage_When_PasswordUpdateProcessFails()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
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

        $this->put('users/1/password');

        $this->assertRedirectedToRoute('users.password.change', [$user]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_PasswordUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->put('users/1/password');

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayEditRolePage_When_EditRolePageIsRequestedAndResourceIsFound()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockRoleRepsitory
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

        $this->get('users/1/role/edit');

        $this->assertResponseOk();
        $this->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_EditRolePageIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('users/1/role/edit');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_RoleUpdateProcessSucceeds()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserForm
            ->shouldReceive('updateRole')
            ->once()
            ->andReturn(true);

        $this->put('users/1/role');

        $this->assertRedirectedToRoute('users.index');
    }

    public function test_Should_RedirectToEditRolePage_When_EditUpdateProcessFails()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
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

        $this->put('users/1/role');

        $this->assertRedirectedToRoute('users.role.edit', [$user]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_RoleUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->put('users/1/role');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $user = Factory::build('App\Models\User', [
            'id'         => 1,
            'name'       => 'User 1',
            'email'      => 'user1@example.com',
            'password'   => '12345678',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($user);

        $this->mockUserRepository
            ->shouldReceive('delete')
            ->once();

        $this->delete('users/1');

        $this->assertRedirectedToRoute('users.index');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockUserRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->delete('users/1');

        $this->assertResponseStatus(404);
    }
}
