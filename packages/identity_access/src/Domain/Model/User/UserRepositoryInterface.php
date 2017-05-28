<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;

interface UserRepositoryInterface
{
    public function allUsers();

    public function usersOfPage($page = 1, $limit = 10);

    public function userOfId(UserId $userId);

    public function userOfEmail($email);

    public function remove(User $user);

    public function save(User $user);

    public function is(User $user, RoleSlug $roleSlug, $operator = null);

    public function can(User $user, $permission, $operator = null);

    public function identityName();

    public function rememberTokenName();
}
