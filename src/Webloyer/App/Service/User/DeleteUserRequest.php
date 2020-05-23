<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

class DeleteUserRequest
{
    /** @var string */
    private $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
}
