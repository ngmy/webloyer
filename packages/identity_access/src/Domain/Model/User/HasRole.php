<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use App;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;

trait HasRole
{
    public function is(RoleSlug $slug, $operator = null)
    {
        $userRepository = App::make(UserRepositoryInterface::class);

        return $userRepository->is($this, $slug, $operator);
    }
}
