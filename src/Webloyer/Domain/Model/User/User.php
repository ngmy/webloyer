<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

use Common\Domain\Model\Identifiable;

class User
{
    use Identifiable;

    /** @var UserEmail */
    private $email;
    /** @var UserName */
    private $name;
    /** @var UserPassword */
    private $password;
    /** @var UserApiToken */
    private $apiToken;

    public static function of(
        string $email,
        string $name,
        string $password,
        string $apiToken
    ): self {
        return new self(
            new UserEmail($email),
            new UserName($name),
            new UserPassword($password),
            new UserApiToken($apiToken)
        );
    }

    /**
     * @param UserEmail    $email
     * @param UserName     $name
     * @param UserPassword $password
     * @param UserApiToken $apiToken
     * @return void
     */
    public function __construct(
        UserEmail $email,
        UserName $name,
        UserPassword $password,
        UserApiToken $apiToken
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->apiToken = $apiToken;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email->value();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name->value();
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password->value();
    }

    /**
     * @return string
     */
    public function apiToken(): string
    {
        return $this->apiToken->value();
    }

    /**
     * @param string $name
     * @return self
     */
    public function changeName(string $name): self
    {
        $this->name = new UserName($name);
        return $this;
    }

    /**
     * @param string $password
     * @return self
     */
    public function changePassword(string $password): self
    {
        $this->password = new UserPassword($password);
        return $this;
    }

    /**
     * @param string $apiToken
     * @return self
     */
    public function changeApiToken(string $apiToken): self
    {
        $this->apiToken = new UserApiToken($apiToken);
        return $this;
    }

    /**
     * @param UserInterest $interest
     * @return void
     */
    public function provide(UserInterest $interest): void
    {
        $interest->informId($this->email());
        $interest->informName($this->name());
        $interest->informPassword($this->password());
        $interest->informApiToken($this->apiToken());
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        $equalObjects = false;

        if ($object instanceof self) {
            $equalObjects = $object->email == $this->email;
        }

        return $equalObjects;
    }
}
