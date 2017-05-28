<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use App;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;

trait Authenticatable
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        $userRepository = App::make(UserRepositoryInterface::class);

        return $userRepository->identityName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->userId()->id();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password();
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        $userRepository = App::make(UserRepositoryInterface::class);

        return $userRepository->rememberTokenName();
    }
}
