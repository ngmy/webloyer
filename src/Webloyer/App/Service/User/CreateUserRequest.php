<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

class CreateUserRequest
{
    /** @var string */
    private $email;
    /** @var string */
    private $name;
    /** @var string */
    private $password;
    /** @var string */
    private $apiToken;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
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

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $apiToken
     * @return self
     */
    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;
        return $this;
    }
}
