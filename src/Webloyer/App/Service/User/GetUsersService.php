<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\Users;

class UserService extends UserService
{
    /**
     * @param GetUsersRequest $request
     * @return Users
     */
    public function execute($request)
    {
        return $this->userRepository->findAllByPage($request->getPage(), $request->getPerPage());
    }
}
