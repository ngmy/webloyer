<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

/**
 * @codeCoverageIgnore
 */
interface UserRepository
{
    /**
     * @return UserId
     */
    public function nextId(): UserId;
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
     * @param UserId $id
     * @return User|null
     */
    public function findById(UserId $id): ?User;
    /**
     * @param UserApiToken $apiToken
     * @return User|null
     */
    public function findByApiToken(UserApiToken $apiToken): ?User;
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
