<?php

namespace App\Services\Form\User;

use App\Repositories\User\UserInterface;
use DB;
use Hash;
use Illuminate\Support\Str;

class UserForm
{
    protected $user;

    /**
     * Create a new form service instance.
     *
     * @param \App\Repositories\User\UserInterface $user
     * @return void
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Create a new user.
     *
     * @param array $input Data to create a user
     * @return boolean
     */
    public function save(array $input)
    {
        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $input['api_token'] = Str::random(60);

        DB::transaction(function () use ($input) {
            $user = $this->user->create($input);

            if (isset($input['role'])) {
                $user->assignRole($input['role']);
            }
        });

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
        DB::transaction(function () use ($input) {
            $this->user->update($input);
        });

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
        $input['password'] = Hash::make($input['password']);

        return $this->user->update($input);
    }

    /**
     * Update a role of an existing user.
     *
     * @param array $input Data to update a user
     * @return boolean
     */
    public function updateRole(array $input)
    {
        if (!isset($input['role'])) {
            $input['role'] = [];
        }

        DB::transaction(function () use ($input) {
            $user = $this->user->byId($input['id']);

            $user->revokeAllRoles();

            if (!empty($input['role'])) {
                $user->assignRole($input['role']);
            }
        });

        return true;
    }

    public function regenerateApiToken(array $input)
    {
        $input['api_token'] = Str::random(60);

        return $this->user->update($input);

        return true;
    }
}
