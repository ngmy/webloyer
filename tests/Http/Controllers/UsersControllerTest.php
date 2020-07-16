<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApplySettings;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Application\Role\RoleService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm\UserForm;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\MockeryHelper;
use TestCase;

class UsersControllerTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    private $userForm;

    private $userService;

    private $roleService;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->mock(User::class);
        $user->shouldReceive('can')->andReturn(true);
        $user->shouldReceive('name');
        $this->auth($user);

        $this->userForm = $this->mock(UserForm::class);
        $this->userService = $this->mock(UserService::class);
        $this->roleService = $this->mock(RoleService::class);

        $this->app->instance(UserForm::class, $this->userForm);
        $this->app->instance(UserService::class, $this->userService);
        $this->app->instance(RoleService::class, $this->roleService);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $user = $this->createUser();
        $users = new Collection([
            $user,
        ]);
        $page = 1;
        $perPage = 10;

        $this->userService
            ->shouldReceive('getUsersByPage')
            ->with($page, $perPage)
            ->andReturn(
                new LengthAwarePaginator(
                    $users,
                    $users->count(),
                    $perPage,
                    $page,
                    [
                        'path' => Paginator::resolveCurrentPath(),
                    ]
                )
            )
            ->once();

        $response = $this->get('users');

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $role = $this->createRole();
        $roles = [
            $role,
        ];

        $this->roleService
            ->shouldReceive('getAllRoles')
            ->withNoArgs()
            ->andReturn($roles)
            ->once();

        $response = $this->get('users/create');

        $response->assertStatus(200);
        $response->assertViewHas('roles');
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->userForm
            ->shouldReceive('save')
            ->andReturn(true)
            ->once();

        $response = $this->post('users');

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
    {
        $this->userForm
            ->shouldReceive('save')
            ->andReturn(false)
            ->once();

        $this->userForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->post('users');

        $response->assertRedirect('users/create');
        $response->assertSessionHasErrors();
    }

    public function test_Should_RedirectToEditPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}");

        $response->assertRedirect("users/{$user->userId()->id()}/edit");
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}");

        $response->assertStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/edit");

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/edit");

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('update')
            ->andReturn(true)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}");

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('update')
            ->andReturn(false)
            ->once();

        $this->userForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->put("users/{$user->userId()->id()}");

        $response->assertRedirect("users/{$user->userId()->id()}/edit");
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}");

        $response->assertStatus(404);
    }

    public function test_Should_DisplayPasswordChangePage_When_PasswordChangePageIsRequestedAndResourceIsFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/password/change");

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_PasswordChangePageIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/password/change");

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_PasswordUpdateProcessSucceeds()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('updatePassword')
            ->andReturn(true)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/password");

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToPasswordChangePage_When_PasswordUpdateProcessFails()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('updatePassword')
            ->andReturn(false)
            ->once();

        $this->userForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/password");

        $response->assertRedirect("users/{$user->userId()->id()}/password/change");
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_PasswordUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/password");

        $response->assertStatus(404);
    }

    public function test_Should_DisplayEditRolePage_When_EditRolePageIsRequestedAndResourceIsFound()
    {
        $user = $this->createUser();
        $role = $this->createRole();
        $roles = [
            $role,
        ];

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();
        $this->roleService
            ->shouldReceive('getAllRoles')
            ->withNoArgs()
            ->andReturn($roles)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/role/edit");

        $response->assertStatus(200);
        $response->assertViewHas('user');
        $response->assertViewHas('roles');
    }

    public function test_Should_DisplayNotFoundPage_When_EditRolePageIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/role/edit");

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_RoleUpdateProcessSucceeds()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('updateRole')
            ->andReturn(true)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/role");

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToEditRolePage_When_EditUpdateProcessFails()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('updateRole')
            ->andReturn(false)
            ->once();

        $this->userForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/role");

        $response->assertRedirect("users/{$user->userId()->id()}/role/edit");
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_RoleUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/role");

        $response->assertStatus(404);
    }

    public function test_Should_DisplayEditApiTokenPage_When_EditApiTokenPageIsRequestedAndResourceIsFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/api_token/edit");

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_Should_DisplayNotFoundPage_When_EditApiTokenPageIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("users/{$user->userId()->id()}/api_token/edit");

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_ApiTokenRegenerateProcessSucceeds()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('regenerateApiToken')
            ->andReturn(true)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/api_token");

        $response->assertRedirect('users');
    }

    public function test_Should_RedirectToRegenerateApiTokenPage_When_ApiTokenRegenerateProcessFails()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userForm
            ->shouldReceive('regenerateApiToken')
            ->andReturn(false)
            ->once();

        $this->userForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/api_token");

        $response->assertRedirect("users/{$user->userId()->id()}/api_token/edit");
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_ApiTokenRegenerateProcessIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->put("users/{$user->userId()->id()}/api_token");

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn($user)
            ->once();

        $this->userService
            ->shouldReceive('removeUser')
            ->with($user->userId()->id())
            ->once();

        $response = $this->delete("users/{$user->userId()->id()}");

        $response->assertRedirect('users');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $user = $this->createUser();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($user->userId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->delete("users/{$user->userId()->id()}");

        $response->assertStatus(404);
    }

    private function createUser(array $params = [])
    {
        $userId = 1;
        $name = '';
        $email = '';
        $password = '';
        $apiToken = '';
        $roleIds = [1];
        $createdAt = null;
        $updatedAt = null;
        $concurrencyVersion = '';

        extract($params);

        $user = $this->mock(User::class);

        $user->shouldReceive('userId')->andReturn(new UserId($userId));
        $user->shouldReceive('name')->andReturn($name);
        $user->shouldReceive('email')->andReturn($email);
        $user->shouldReceive('password')->andReturn($password);
        $user->shouldReceive('apiToken')->andReturn($apiToken);
        $user->shouldReceive('roleIds')->andReturn(array_map(function ($roleId) {
            return new RoleId($roleId);
        }, $roleIds));
        $user->shouldReceive('createdAt')->andReturn(new Carbon($createdAt));
        $user->shouldReceive('updatedAt')->andReturn(new Carbon($updatedAt));
        $user->shouldReceive('concurrencyVersion')->andReturn($concurrencyVersion);

        $user->shouldReceive('hasRoleId')->andReturn(false)->byDefault();
        foreach ($roleIds as $roleId) {
            $user->shouldReceive('hasRoleId')->with(\Hamcrest\Matchers::equalTo(new RoleId($roleId)))->andReturn(true);
        }

        return $user;
    }

    private function createRole(array $params = [])
    {
        $roleId = 1;
        $name = '';
        $slug = 'administrator';
        $description = '';

        extract($params);

        $role = $this->mock(Role::class);

        $role->shouldReceive('roleId')->andReturn(new RoleId($roleId));
        $role->shouldReceive('name')->andReturn($name);
        $role->shouldReceive('slug')->andReturn(new RoleSlug($slug));
        $role->shouldReceive('description')->andReturn($description);

        return $role;
    }
}
