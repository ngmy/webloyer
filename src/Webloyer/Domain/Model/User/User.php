<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

use Common\Domain\Model\Identity\Identifiable;
use Common\Domain\Model\Timestamp\Timestampable;

abstract class User
{
    use Identifiable;
    use Timestampable;

    /** @var UserId */
    protected $id;
    /** @var UserEmail */
    protected $email;
    /** @var UserName */
    protected $name;
    /** @var UserPassword */
    protected $password;
    /** @var UserApiToken|null */
    protected $apiToken;
    /** @var UserRoles */
    protected $roles;

    /**
     * @return string
     */
    abstract public function id(): string;
    /**
     * @return string
     */
    abstract public function email(): string;
    /**
     * @return string
     */
    abstract public function name(): string;
    /**
     * @return string
     */
    abstract public function password(): string;
    /**
     * @return string|null
     */
    abstract public function apiToken(): ?string;
    /**
     * @return list<string>
     */
    abstract public function roles(): array;
    /**
     * @param string $email
     * @return self
     */
    abstract public function changeEmail(string $email): self;
    /**
     * @param string $name
     * @return self
     */
    abstract public function changeName(string $name): self;
    /**
     * @param string $password
     * @return self
     */
    abstract public function changePassword(string $password): self;
    /**
     * @param string $apiToken
     * @return self
     */
    abstract public function changeApiToken(string $apiToken): self;
    /**
     * @param UserInterest $interest
     * @return void
     */
    abstract public function provide(UserInterest $interest): void;
    /**
     * @param mixed $object
     * @return bool
     */
    abstract public function equals($object): bool;
    /**
     * @param UserRoleSpecification $roleSpec
     * @return void
     */
    abstract public function addRole(UserRoleSpecification $roleSpec): void;
    /**
     * @param UserRoleSpecification $roleSpec
     * @return bool
     */
    abstract public function hasRole(UserRoleSpecification $roleSpec): bool;
    /**
     * @param UserRoleSpecification $roleSpec
     * @return void
     */
    abstract public function removeRole(UserRoleSpecification $roleSpec): void;
    /**
     * @return void
     */
    abstract public function removeAllRoles(): void;
    /**
     * @param UserRoleSpecification $roleSpec
     * @return UserRole|null
     */
    abstract public function getRole(UserRoleSpecification $roleSpec): ?UserRole;

    /**
     * @param string      $id
     * @param string      $email
     * @param string      $name
     * @param string      $password
     * @param string|null $apiToken
     * @return UserCore
     */
    public static function of(
        string $id,
        string $email,
        string $name,
        string $password,
        ?string $apiToken
    ): UserCore {
        return new UserCore(
            new UserId($id),
            new UserEmail($email),
            new UserName($name),
            new UserPassword($password),
            isset($apiToken) ? new UserApiToken($apiToken) : null
        );
    }

    /**
     * @param string       $id
     * @param string       $email
     * @param string       $name
     * @param string       $password
     * @param string|null  $apiToken
     * @param list<string> $roles
     * @return UserCore
     */
    public static function ofWithRole(
        string $id,
        string $email,
        string $name,
        string $password,
        ?string $apiToken,
        array $roles
    ): UserCore {
        $user = self::of(
            $id,
            $email,
            $name,
            $password,
            $apiToken
        );
        array_map(function (string $role) use ($user) {
            $user->addRole(UserRoleSpecification::$role());
        }, $roles);
        return $user;
    }
}
