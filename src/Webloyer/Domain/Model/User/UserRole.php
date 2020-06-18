<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

abstract class UserRole extends User
{
    /** @var UserCore */
    private $core;

    /**
     * @param UserRoleSpecification $roleSpec
     * @param UserCore              $core
     * @return UserRole
     */
    public static function createFor(UserRoleSpecification $roleSpec, UserCore $core): UserRole
    {
        $role = $roleSpec->create();
        $role->core = $core;
        return $role;
    }

    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->core->id();
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->core->email();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->core->name();
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->core->password();
    }

    /**
     * @return string|null
     */
    public function apiToken(): ?string
    {
        return $this->core->apiToken();
    }

    /**
     * @return list<string>
     */
    public function roles(): array
    {
        return $this->roles->toArray();
    }

    /**
     * @param string $email
     * @return UserCore
     */
    public function changeEmail(string $email): UserCore
    {
        return $this->core->changeEmail($email);
    }

    /**
     * @param string $name
     * @return UserCore
     */
    public function changeName(string $name): UserCore
    {
        return $this->core->changeName($name);
    }

    /**
     * @param string $password
     * @return UserCore
     */
    public function changePassword(string $password): UserCore
    {
        return $this->core->changePassword($password);
    }

    /**
     * @param string $apiToken
     * @return UserCore
     */
    public function changeApiToken(string $apiToken): UserCore
    {
        return $this->core->changeApiToken($apiToken);
    }

    /**
     * @param UserInterest $interest
     * @return void
     */
    public function provide(UserInterest $interest): void
    {
        $this->core->provide($interest);
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        return $this->core->equals($object);
    }

    /**
     * @param UserRoleSpecification $roleSpec
     * @return void
     */
    public function addRole(UserRoleSpecification $roleSpec): void
    {
        $this->core->addRole($roleSpec);
    }

    /**
     * @param UserRoleSpecification $roleSpec
     * @return bool
     */
    public function hasRole(UserRoleSpecification $roleSpec): bool
    {
        return $this->core->hasRole($roleSpec);
    }

    /**
     * @param UserRoleSpecification $roleSpec
     * @return void
     */
    public function removeRole(UserRoleSpecification $roleSpec): void
    {
        $this->core->removeRole($roleSpec);
    }

    /**
     * @return void
     */
    public function removeAllRoles(): void
    {
        $this->core->removeAllRoles();
    }

    /**
     * @param UserRoleSpecification $roleSpec
     * @return UserRole|null
     */
    public function getRole(UserRoleSpecification $roleSpec): ?UserRole
    {
        return $this->core->getRole($roleSpec);
    }
}
