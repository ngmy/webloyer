<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\Users;

class GetUsersService extends UserService
{
    /**
     * @param GetUsersRequest $request
     * @return Users
     */
    public function execute($request = null)
    {
        return $this->userRepository->findAllByPage($request->getPage(), $request->getPerPage());
    }
}
