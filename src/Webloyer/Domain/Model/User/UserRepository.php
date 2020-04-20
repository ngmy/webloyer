<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

/**
 * @codeCoverageIgnore
 */
interface UserRepository
{
    /**
     * @return Users
     */
    public function findAll(): Users;
    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Users
     */
    public function findAllByPage(?int $page, ?int $perPage): Users;
    /**
     * @param UserEmail $email
     * @return User|null
     */
    public function findByEmail(UserEmail $email): ?User;
    /**
     * @param User $recipe
     * @return void
     */
    public function remove(User $recipe): void;
    /**
     * @param User $recipe
     * @return void
     */
    public function save(User $recipe): void;
}
