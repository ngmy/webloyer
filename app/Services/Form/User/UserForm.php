<?php

namespace App\Services\Form\User;

use Hash;
use App\Services\Validation\ValidableInterface;
use App\Repositories\User\UserInterface;

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

        return $this->user->create($input);
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

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        return $this->user->update($input);
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
