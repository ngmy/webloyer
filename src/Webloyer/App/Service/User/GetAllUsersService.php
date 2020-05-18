<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\Users;

class GetAllUsersService extends UserService
{
    /**
     * @return Users
     */
    public function execute($request = null)
    {
        return $this->userRepository->findAll();
    }
}
