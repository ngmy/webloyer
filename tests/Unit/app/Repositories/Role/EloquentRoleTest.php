<?php

namespace Tests\Unit\app\Repositories\Role;

use App\Repositories\Role\EloquentRole;

use Tests\Helpers\Factory;

class EloquentRoleTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetRoleById()
    {
        $arrangedRole = Factory::create('Kodeine\Acl\Models\Eloquent\Role', [
            'name'        => 'Role 1',
            'slug'        => 'role_1',
            'description' => '',
        ]);

        $serverRepository = new EloquentRole(new Kodeine\Acl\Models\Eloquent\Role);

        $foundRole = $serverRepository->byId($arrangedRole->id);

        $this->assertEquals('Role 1', $foundRole->name);
        $this->assertEquals('role_1', $foundRole->slug);
        $this->assertEquals('', $foundRole->description);
    }

    public function test_Should_GetRolesByPage()
    {
        Factory::createList('Kodeine\Acl\Models\Eloquent\Role', [
            ['name' => 'Role 1', 'slug' => 'role_1', 'description' => ''],
            ['name' => 'Role 2', 'slug' => 'role_2', 'description' => ''],
            ['name' => 'Role 3', 'slug' => 'role_3', 'description' => ''],
            ['name' => 'Role 4', 'slug' => 'role_4', 'description' => ''],
            ['name' => 'Role 5', 'slug' => 'role_5', 'description' => ''],
        ]);

        $serverRepository = new EloquentRole(new Kodeine\Acl\Models\Eloquent\Role);

        $foundRoles = $serverRepository->byPage();

        $this->assertCount(5, $foundRoles->items());
    }

    public function test_Should_CreateNewRole()
    {
        $serverRepository = new EloquentRole(new Kodeine\Acl\Models\Eloquent\Role);

        $returnedRole = $serverRepository->create([
            'name'        => 'Role 1',
            'slug'        => 'role_1',
            'description' => '',
        ]);

        $server = new Kodeine\Acl\Models\Eloquent\Role;
        $createdRole = $server->find($returnedRole->id);

        $this->assertEquals('Role 1', $createdRole->name);
        $this->assertEquals('role_1', $createdRole->slug);
        $this->assertEquals('', $createdRole->description);
    }

    public function test_Should_UpdateExistingRole()
    {
        $arrangedRole = Factory::create('Kodeine\Acl\Models\Eloquent\Role', [
            'name'        => 'Role 1',
            'slug'        => 'role_1',
            'description' => '',
        ]);

        $serverRepository = new EloquentRole(new Kodeine\Acl\Models\Eloquent\Role);

        $serverRepository->update([
            'id'          => $arrangedRole->id,
            'name'        => 'Role 2',
            'slug'        => 'role_2',
            'description' => 'Role 2.',
        ]);

        $server = new Kodeine\Acl\Models\Eloquent\Role;
        $updatedRole = $server->find($arrangedRole->id);

        $this->assertEquals('Role 2', $updatedRole->name);
        $this->assertEquals('role_2', $updatedRole->slug);
        $this->assertEquals('Role 2.', $updatedRole->description);
    }

    public function test_Should_DeleteExistingRole()
    {
        $arrangedRole = Factory::create('Kodeine\Acl\Models\Eloquent\Role', [
            'name'        => 'Role 1',
            'slug'        => 'role_1',
            'description' => '',
        ]);

        $serverRepository = new EloquentRole(new Kodeine\Acl\Models\Eloquent\Role);

        $serverRepository->delete($arrangedRole->id);

        $server = new Kodeine\Acl\Models\Eloquent\Role;
        $deletedRole = $server->find($arrangedRole->id);

        $this->assertNull($deletedRole);
    }
}
