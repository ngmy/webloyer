<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence;

use Illuminate\Auth\EloquentUserProvider as LaravelEloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Str;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\EloquentUserRepository;

class EloquentUserProvider extends LaravelEloquentUserProvider
{
    private $userRepository;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherContract $hasher, $model, EloquentUserRepository $userRepository)
    {
        parent::__construct($hasher, $model);

        $this->userRepository = $userRepository;
    }

    /**
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $eloquentUser = parent::retrieveById($identifier);

        $user = $eloquentUser->toEntity();

        return $user;
    }

    /**
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $eloquentUser = parent::retrieveByToken($identifier, $token);

        $user = $eloquentUser->toEntity();

        return $user;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        $eloquentUser = $this->userRepository->toEloquent($user);

        parent::updateRememberToken($eloquentUser, $token);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $eloquentUser = parent::retrieveByCredentials($credentials);

        $user = $eloquentUser->toEntity();

        return $user;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $eloquentUser = $this->userRepository->toEloquent($user);

        return parent::validateCredentials($eloquentUser, $credentials);
    }
}
