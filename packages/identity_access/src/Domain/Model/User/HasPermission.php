<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use App;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;

trait HasPermission
{
    public function can($permission, $operator = null)
    {
        $userRepository = App::make(UserRepositoryInterface::class);

        return $userRepository->can($this, $permission, $operator);
    }
}
