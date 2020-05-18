<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\{
    User,
    UserEmail,
};

class GetUserService extends UserService
{
    /**
     * @param GetUserRequest $request
     * @return User
     */
    public function execute($request = null)
    {
        $email = new UserEmail($request->getEmail());
        return $this->getNonNullUser($email);
    }
}
