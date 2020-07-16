<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence;

use Illuminate\Database\Eloquent\Collection;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\EloquentRoleRepository;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\Role as EloquentRole;
use Tests\Helpers\EloquentFactory;
use TestCase;

class EloquentRoleRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetRoleOfId()
    {
        $createdEloquentRole = EloquentFactory::create(EloquentRole::class, [
            'name'        => 'Administrator',
            'slug'        => 'administrator',
            'description' => '',
        ]);
        $expectedResult = $createdEloquentRole->toEntity();

        $actualResult = $this->createEloquentRoleRepository()->roleOfId($expectedResult->roleId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRoleOfSlug()
    {
        $createdEloquentRole = EloquentFactory::create(EloquentRole::class, [
            'name'        => 'Administrator',
            'slug'        => 'administrator',
            'description' => '',
        ]);
        $expectedResult = $createdEloquentRole->toEntity();

        $actualResult = $this->createEloquentRoleRepository()->roleOfSlug($expectedResult->slug());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllRoles()
    {
        $createdEloquentRoles = EloquentFactory::createList(EloquentRole::class, [
            [
                'name'        => 'Administrator',
                'slug'        => 'administrator',
                'description' => '',
            ],
            [
                'name'        => 'Developer',
                'slug'        => 'developer',
                'description' => '',
            ],
            [
                'name'        => 'Operator',
                'slug'        => 'operator',
                'description' => '',
            ],
        ]);
        $expectedResult = (new Collection(array_map(function ($eloquentRole) {
            return $eloquentRole->toEntity();
        }, $createdEloquentRoles)))->all();

        $actualResult = $this->createEloquentRoleRepository()->allRoles();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createEloquentRoleRepository(array $params = [])
    {
        extract($params);

        return new EloquentRoleRepository(new EloquentRole());
    }
}
