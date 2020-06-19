<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

class NullUser extends User
{
    /** @var self|null */
    private static $instance;

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public function id(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function email(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function password(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function apiToken(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function roles(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function changeEmail(string $email): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changeName(string $name): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword(string $password): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changeApiToken(string $apiToken): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(UserInterest $interest): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole(UserRoleSpecification $roleSpec): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole(UserRoleSpecification $roleSpec): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole(UserRoleSpecification $roleSpec): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllRoles(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRole(UserRoleSpecification $roleSpec): ?UserRole
    {
        return null;
    }

    /**
     * @return void
     */
    private function __construct() {
    }
}
