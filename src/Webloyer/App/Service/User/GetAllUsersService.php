<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

class GetAllUsersService extends UserService
{
    /**
     * @return mixed
     */
    public function execute($request = null)
    {
        $users = $this->userRepository->findAll();
        return $this->usersDataTransformer->write($users)->read();
    }
}
