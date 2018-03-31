<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm;

use Hash;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;

class UserForm
{
    private $validator;

    private $userService;

    /**
     * Create a new form service instance.
     *
     * @param \Ngmy\Webloyer\Common\Validation\ValidableInterface        $validator
     * @param \Ngmy\Webloyer\IdentityAccess\Application\User\UserService $userService
     * @return void
     */
    public function __construct(ValidableInterface $validator, UserService $userService)
    {
        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * Create a new user.
     *
     * @param array $input Data to create a user
     * @return \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User|false
     */
    public function save(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

        $hashedPassword = Hash::make($input['password']);

        $apiToken = str_random(60);

        $this->userService->saveUser(
            null,
            $input['name'],
            $input['email'],
            $hashedPassword,
            $apiToken,
            $input['role'],
            null
        );

        return true;
    }

    /**
     * Update an existing user.
     *
     * @param array $input Data to update a user
     * @return boolean
     */
    public function update(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

        $user = $this->userService->getUserById($input['id']);

        $this->userService->saveUser(
            $input['id'],
            $input['name'],
            $input['email'],
            $user->password(),
            $user->apiToken(),
            array_map(function (RoleId $roleId) {
                return $roleId->id();
            }, $user->roleIds()),
            $input['concurrency_version']
        );

        return true;
    }

    /**
     * Update a password of an existing user.
     *
     * @param array $input Data to update a user
     * @return boolean
     */
    public function updatePassword(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

        $hashedPassword = Hash::make($input['password']);

        $user = $this->userService->getUserById($input['id']);

        $this->userService->saveUser(
            $input['id'],
            $user->name(),
            $user->email(),
            $hashedPassword,
            $user->apiToken(),
            array_map(function (RoleId $roleId) {
                return $roleId->id();
            }, $user->roleIds()),
            $input['concurrency_version']
        );

        return true;
    }

    /**
     * Update a role of an existing user.
     *
     * @param array $input Data to update a user
     * @return boolean
     */
    public function updateRole(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

        if (!isset($input['role'])) {
            $input['role'] = [];
        }

        $user = $this->userService->getUserById($input['id']);

        $this->userService->saveUser(
            $input['id'],
            $user->name(),
            $user->email(),
            $user->password(),
            $user->apiToken(),
            $input['role'],
            $input['concurrency_version']
        );

        return true;
    }

    public function regenerateApiToken(array $input)
    {
        $apiToken = str_random(60);

        $user = $this->userService->getUserById($input['id']);

        $this->userService->saveUser(
            $input['id'],
            $user->name(),
            $user->email(),
            $user->password(),
            $apiToken,
            array_map(function (RoleId $roleId) {
                return $roleId->id();
            }, $user->roleIds()),
            $input['concurrency_version']
        );

        return true;
    }

    /**
     * Return validation errors.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test whether form validator passes.
     *
     * @param array $input Data to test whether form validator passes
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
