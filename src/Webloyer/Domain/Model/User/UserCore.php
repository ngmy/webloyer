<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

class UserCore extends User
{
    /**
     * @param UserId            $id
     * @param UserEmail         $email
     * @param UserName          $name
     * @param UserPassword      $password
     * @param UserApiToken|null $apiToken
     * @return void
     */
    public function __construct(
        UserId $id,
        UserEmail $email,
        UserName $name,
        UserPassword $password,
        ?UserApiToken $apiToken
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->apiToken = $apiToken;
        $this->roles = UserRoles::empty();
    }

    /**
     * {@inheritdoc}
     */
    public function id(): string
    {
        return $this->id->value();
    }

    /**
     * {@inheritdoc}
     */
    public function email(): string
    {
        return $this->email->value();
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->name->value();
    }

    /**
     * {@inheritdoc}
     */
    public function password(): string
    {
        return $this->password->value();
    }

    /**
     * {@inheritdoc}
     */
    public function apiToken(): ?string
    {
        return isset($this->apiToken) ? $this->apiToken->value() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function roles(): array
    {
        return $this->roles->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function changeEmail(string $email): self
    {
        $this->email = new UserEmail($email);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changeName(string $name): self
    {
        $this->name = new UserName($name);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword(string $password): self
    {
        $this->password = new UserPassword($password);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changeApiToken(string $apiToken): self
    {
        $this->apiToken = new UserApiToken($apiToken);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(UserInterest $interest): void
    {
        $interest->informId($this->id());
        $interest->informEmail($this->email());
        $interest->informName($this->name());
        $interest->informPassword($this->password());
        $interest->informApiToken($this->apiToken());
        $interest->informRoles($this->roles());
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
    {
        $equalObjects = false;

        if ($object instanceof self) {
            $equalObjects = $object->id == $this->id;
        }

        return $equalObjects;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function hasRole(UserRoleSpecification $roleSpec): bool
    {
        return $this->roles->has($roleSpec);
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole(UserRoleSpecification $roleSpec): void
    {
        $this->roles->remove($roleSpec);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllRoles(): void
    {
        $this->roles->removeAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getRole(UserRoleSpecification $roleSpec): ?UserRole
    {
        return $this->roles->get($roleSpec);
    }
}
