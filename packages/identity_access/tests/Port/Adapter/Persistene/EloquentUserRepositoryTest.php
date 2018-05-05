<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Kodeine\Acl\Models\Eloquent\Permission as EloquentPermission;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\EloquentUserRepository;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\User as EloquentUser;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\Role as EloquentRole;
use Tests\Helpers\EloquentFactory;
use TestCase;

class EloquentUserRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function isDataProvider()
    {
        return [
            'UserIsSpecifiedRole' => [
                RoleSlug::administrator(),
                RoleSlug::administrator(),
                true,
            ],
            'UserIsNotSpecifiedRole' => [
                RoleSlug::administrator(),
                RoleSlug::developer(),
                false,
            ],
        ];
    }

    public function canDataProvider()
    {
        return [
            'UserCanSpecifiedPermission' => [
                [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => true,
                ],
                'create',
                true,
            ],
            'UserCanNotSpecifiedPermission' => [
                [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => false,
                ],
                'delete',
                true,
            ],
        ];
    }

    public function test_Should_GetUserOfId()
    {
        $createdEloquentUser = EloquentFactory::create(EloquentUser::class, [
            'created_at' => '2018-04-30 12:00:00',
            'updated_at' => '2018-04-30 12:00:00',
        ]);
        $expectedResult = $createdEloquentUser->toEntity();

        $actualResult = $this->createEloquentUserRepository()->userOfId($expectedResult->userId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUserOfEmail()
    {
        $createdEloquentUser = EloquentFactory::create(EloquentUser::class, [
            'created_at' => '2018-04-30 12:00:00',
            'updated_at' => '2018-04-30 12:00:00',
        ]);
        $expectedResult = $createdEloquentUser->toEntity();

        $actualResult = $this->createEloquentUserRepository()->userOfEmail($expectedResult->email());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllUsers()
    {
        $createdEloquentUsers = EloquentFactory::createList(EloquentUser::class, [
            [
                'email'      => 'user1@example.com',
                'api_token'  => 'user1_api_token',
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'email'      => 'user2@example.com',
                'api_token'  => 'user2_api_token',
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'email'      => 'user3@example.com',
                'api_token'  => 'user3_api_token',
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $expectedResult = (new Collection(array_map(function ($eloquentUser) {
            return $eloquentUser->toEntity();
        }, $createdEloquentUsers)))->all();

        $actualResult = $this->createEloquentUserRepository()->allUsers();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUsersOfPage()
    {
        $createdEloquentUsers = EloquentFactory::createList(EloquentUser::class, [
            [
                'email'      => 'user1@example.com',
                'api_token'  => 'user1_api_token',
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'email'      => 'user2@example.com',
                'api_token'  => 'user2_api_token',
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'email'      => 'user3@example.com',
                'api_token'  => 'user3_api_token',
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $createdUsers = new Collection(array_map(function ($eloquentUser) {
            return $eloquentUser->toEntity();
        }, $createdEloquentUsers));
        $page = 1;
        $limit = 10;
        $expectedResult = new LengthAwarePaginator(
            $createdUsers,
            $createdUsers->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );

        $actualResult = $this->createEloquentUserRepository()->usersOfPage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewUser()
    {
        $createdRole = EloquentFactory::create(EloquentRole::class);

        $newUser = $this->createUser([
            'roleIds' => [$createdRole->id],
        ]);

        $returnedUser = $this->createEloquentUserRepository()->save($newUser);

        $createdEloquentUser = EloquentUser::find($returnedUser->UserId()->id());

        $this->assertEquals($newUser->email(), $createdEloquentUser->email);

        $this->assertEquals($newUser->email(), $returnedUser->email());

        $this->assertEquals($createdEloquentUser->created_at, $returnedUser->createdAt());
        $this->assertEquals($createdEloquentUser->updated_at, $returnedUser->createdAt());
    }

    public function test_Should_UpdateExistingUser()
    {
        $eloquentUserShouldBeUpdated = EloquentFactory::create(EloquentUser::class);

        $newUser = $this->createUser([
            'userId' => $eloquentUserShouldBeUpdated->id,
            'name'   => 'new name',
        ]);

        $returnedUser = $this->createEloquentUserRepository()->save($newUser);

        $updatedEloquentUser = EloquentUser::find($eloquentUserShouldBeUpdated->id);

        $this->assertEquals($newUser->name(), $updatedEloquentUser->name);

        $this->assertEquals($newUser->name(), $returnedUser->name());

        $this->assertEquals($updatedEloquentUser->created_at, $returnedUser->createdAt());
        $this->assertEquals($updatedEloquentUser->updated_at, $returnedUser->updatedAt());
    }

    public function test_Should_DeleteExistingUser()
    {
        $eloquentUserShouldBeDeleted = EloquentFactory::create(EloquentUser::class);

        $this->createEloquentUserRepository()->remove($eloquentUserShouldBeDeleted->toEntity());

        $deletedEloquentUser = EloquentUser::find($eloquentUserShouldBeDeleted->id);

        $this->assertNull($deletedEloquentUser);
    }

    /**
     * @dataProvider isDataProvider
     */
    public function test_Should_VerifyUserRole_When_(RoleSlug $userRoleSlug, RoleSlug $specifiedRoleSlug, $expectedResult)
    {
        $createdEloquentRole = EloquentFactory::create(EloquentRole::class, [
            'slug' => $userRoleSlug->value(),
        ]);
        $createdEloquentUser = EloquentFactory::create(EloquentUser::class);
        $createdEloquentUser->assignRole([$createdEloquentRole->id]);

        $user = $this->createUser([
            'userId' => $createdEloquentUser->id,
        ]);

        $actualResult = $this->createEloquentUserRepository()->is($user, $specifiedRoleSlug);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider canDataProvider
     */
    public function test_Should_VerifyUserPermission($userPermission, $specifiedPermission, $expectedResult)
    {
    }

    public function test_Should_GetIdentityName()
    {
        $expectedResult = 'id';

        $actualResult = $this->createEloquentUserRepository()->identityName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRememberTokenName()
    {
        $expectedResult = 'remember_token';

        $actualResult = $this->createEloquentUserRepository()->rememberTokenName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createUser(array $params = [])
    {
        $userId = null;
        $name = '';
        $email = '';
        $password = '';
        $apiToken = '';
        $roleIds = [];
        $createdAt = '';
        $updatedAt = '';

        extract($params);

        return new User(
            new UserId($userId),
            $name,
            $email,
            $password,
            $apiToken,
            array_map(function ($roleId) {
                return new RoleId($roleId);
            }, $roleIds),
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }

    private function createEloquentUserRepository(array $params = [])
    {
        extract($params);

        return new EloquentUserRepository(new EloquentUser());
    }
}
