<?php

namespace App\Services\Form\User;

use DB;
use Hash;
use App\Services\Validation\ValidableInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Support\Str;

class UserForm
{
    protected $validator;

    protected $user;

    /**
     * Create a new form service instance.
     *
     * @param \App\Services\Validation\ValidableInterface $validator
     * @param \App\Repositories\User\UserInterface        $user
     * @return void
     */
    public function __construct(ValidableInterface $validator, UserInterface $user)
    {
        $this->validator = $validator;
        $this->user      = $user;
    }

    /**
     * Create a new user.
     *
     * @param array $input Data to create a user
     * @return boolean
     */
    public function save(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

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
        if (!$this->valid($input)) {
            return false;
        }

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
        if (!$this->valid($input)) {
            return false;
        }

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
        if (!$this->valid($input)) {
            return false;
        }

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

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test whether form validator passes.
     *
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
