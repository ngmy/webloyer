<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

class UserRoles
{
    /** @var array<string, UserRole> */
    private $roles;

    /**
     * @return self
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @param array<string, UserRole> $roles
     * @return void
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return list<string>
     */
    public function toArray(): array
    {
        return array_keys($this->roles);
    }

    /**
     * @param UserRoleSpecification $spec
     * @param UserRole     $role
     * @return void
     */
    public function add(UserRoleSpecification $spec, UserRole $role): void
    {
        if ($this->has($spec)) {
            return;
        }
        $this->roles[$spec->slug()] = $role;
    }

    /**
     * @param UserRoleSpecification $spec
     * @return bool
     */
    public function has(UserRoleSpecification $spec): bool
    {
        return isset($this->roles[$spec->value()]);
    }

    /**
     * @param UserRoleSpecification $spec
     * @return void
     */
    public function remove(UserRoleSpecification $spec): void
    {
        if (!$this->has($spec)) {
            return;
        }
        unset($this->roles[$spec->value()]);
    }

    public function removeAll(): void
    {
        $this->roles = [];
    }

    /**
     * @param UserRoleSpecification $spec
     * @return UserRole|null
     */
    public function get(UserRoleSpecification $spec): ?UserRole
    {
        return $this->has($spec) ? $this->roles[$spec->value()] : null;
    }
}
