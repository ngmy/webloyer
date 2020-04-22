<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

class UserCore extends User
{
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
        $this->roles = UserRoles::empty();
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
     * @return array<int, string>
     */
    public function roles(): array
    {
        return $this->roles->toArray();
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
        $interest->informEmail($this->email());
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

    /**
     * @param UserRoleSpecification $roleSpec
     * @return void
     */
    public function addRole(UserRoleSpecification $roleSpec): void
    {
        if (!is_null($this->getRole($roleSpec))) {
            return;
        }
        $this->roles->add(
            $roleSpec,
            UserRole::createFor($roleSpec, $this)
        );
    }

    /**
     * @param UserRoleSpecification $roleSpec
     * @return bool
     */
    public function hasRole(UserRoleSpecification $roleSpec): bool
    {
        return $this->roles->has($roleSpec);
    }

    /**
     * @param UserRoleSpecification $roleSpec
     * @return void
     */
    public function removeRole(UserRoleSpecification $roleSpec): void
    {
        $this->roles->remove($roleSpec);
    }

    /**
     * @param UserRoleSpecification $roleSpec
     * @return UserRole|null
     */
    public function getRole(UserRoleSpecification $roleSpec): ?UserRole
    {
        return $this->roles->get($roleSpec);
    }
}
