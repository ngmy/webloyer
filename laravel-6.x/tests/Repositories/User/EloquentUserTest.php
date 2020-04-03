<?php

use App\Repositories\User\EloquentUser;

use Tests\Helpers\Factory;
use Kodeine\Acl\Models\Eloquent\Role;

class EloquentUserTest extends TestCase
{
    protected $useDatabase = true;

    protected $role;

    public function setUp()
    {
        parent::setUp();

        $this->role = new Role;
    }

    public function test_Should_GetUserById()
    {
        $arrangedUser = Factory::create('App\Models\User', [
            'name'      => 'User 1',
            'email'     => 'user1@example.com',
            'password'  => '12345678',
            'api_token' => '12345678',
        ]);

        $userRepository = new EloquentUser(new App\Models\User);

        $foundUser = $userRepository->byId($arrangedUser->id);

        $this->assertEquals('User 1', $foundUser->name);
        $this->assertEquals('user1@example.com', $foundUser->email);
        $this->assertEquals('12345678', $foundUser->password);
        $this->assertEquals('12345678', $foundUser->api_token);
    }

    public function test_Should_GetUsersByPage()
    {
        Factory::createList('App\Models\User', [
            ['name' => 'User 1', 'email' => 'user1@example.com', 'password' => '12345678', 'api_token' => '12345678'],
            ['name' => 'User 2', 'email' => 'user2@example.com', 'password' => '23456789', 'api_token' => '23456789'],
            ['name' => 'User 3', 'email' => 'user3@example.com', 'password' => '34567890', 'api_token' => '34567890'],
            ['name' => 'User 4', 'email' => 'user4@example.com', 'password' => '4567890a', 'api_token' => '45678901'],
            ['name' => 'User 5', 'email' => 'user5@example.com', 'password' => '567890ab', 'api_token' => '56789012'],
        ]);

        $userRepository = new EloquentUser(new App\Models\User);

        $foundUsers = $userRepository->byPage();

        $this->assertCount(5, $foundUsers->items());
    }

    public function test_Should_CreateNewUser()
    {
        $userRepository = new EloquentUser(new App\Models\User);

        $returnedUser = $userRepository->create([
            'name'      => 'User 1',
            'email'     => 'user1@example.com',
            'password'  => '12345678',
            'api_token' => '12345678',
        ]);

        $user = new App\Models\User;
        $createdUser = $user->find($returnedUser->id);

        $this->assertEquals('User 1', $createdUser->name);
        $this->assertEquals('user1@example.com', $createdUser->email);
        $this->assertEquals('12345678', $createdUser->password);
        $this->assertEquals('12345678', $createdUser->api_token);
    }

    public function test_Should_UpdateExistingUser()
    {
        $arrangedUser = Factory::create('App\Models\User', [
            'name'      => 'User 1',
            'email'     => 'user1@example.com',
            'password'  => '12345678',
            'api_token' => '12345678',
        ]);

        $userRepository = new EloquentUser(new App\Models\User);

        $userRepository->update([
            'id'        => $arrangedUser->id,
            'name'      => 'User 2',
            'email'     => 'user2@example.com',
            'password'  => '23456789',
            'api_token' => '23456789',
        ]);

        $user = new App\Models\User;
        $updatedUser = $user->find($arrangedUser->id);

        $this->assertEquals('User 2', $updatedUser->name);
        $this->assertEquals('user2@example.com', $updatedUser->email);
        $this->assertEquals('23456789', $updatedUser->password);
        $this->assertEquals('23456789', $updatedUser->api_token);
    }

    public function test_Should_UpdateExistingUser_When_RoleIsSpecified()
    {
        $role = $this->role->create([
            'name' => 'Role 1',
            'slug' => 'role1',
        ]);

        $arrangedUser = Factory::create('App\Models\User', [
            'name'      => 'User 1',
            'email'     => 'user1@example.com',
            'password'  => '12345678',
            'api_token' => '12345678',
        ]);

        $arrangedUser->assignRole('role1');

        $userRepository = new EloquentUser(new App\Models\User);

        $userRepository->update([
            'id'        => $arrangedUser->id,
            'name'      => 'User 2',
            'email'     => 'user2@example.com',
            'password'  => '23456789',
            'api_token' => '23456789',
        ]);

        $user = new App\Models\User;
        $updatedUser = $user->find($arrangedUser->id);

        $this->assertEquals('User 2', $updatedUser->name);
        $this->assertEquals('user2@example.com', $updatedUser->email);
        $this->assertEquals('23456789', $updatedUser->password);
        $this->assertEquals('23456789', $updatedUser->api_token);
        $this->assertEquals('role1', $updatedUser->getRoles()[$role->id]);
    }

    public function test_Should_UpdateExistingUser_When_RoleIsEmpty()
    {
        $role = $this->role->create([
            'name' => 'Role 1',
            'slug' => 'role1',
        ]);

        $arrangedUser = Factory::create('App\Models\User', [
            'name'      => 'User 1',
            'email'     => 'user1@example.com',
            'password'  => '12345678',
            'api_token' => '12345678',
        ]);

        $arrangedUser->assignRole('role1');

        $userRepository = new EloquentUser(new App\Models\User);

        $userRepository->update([
            'id'        => $arrangedUser->id,
            'name'      => 'User 2',
            'email'     => 'user2@example.com',
            'password'  => '23456789',
            'api_token' => '23456789',
        ]);

        $user = new App\Models\User;
        $updatedUser = $user->find($arrangedUser->id);

        $this->assertEquals('User 2', $updatedUser->name);
        $this->assertEquals('user2@example.com', $updatedUser->email);
        $this->assertEquals('23456789', $updatedUser->password);
        $this->assertEquals('23456789', $updatedUser->api_token);
        $this->assertEquals('role1', $updatedUser->getRoles()[$role->id]);
    }

    public function test_Should_DeleteExistingUser()
    {
        $arrangedUser = Factory::create('App\Models\User', [
            'name'      => 'User 1',
            'email'     => 'user1@example.com',
            'password'  => '12345678',
            'api_token' => '12345678',
        ]);

        $userRepository = new EloquentUser(new App\Models\User);

        $userRepository->delete($arrangedUser->id);

        $user = new App\Models\User;
        $deletedUser = $user->find($arrangedUser->id);

        $this->assertNull($deletedUser);
    }
}
