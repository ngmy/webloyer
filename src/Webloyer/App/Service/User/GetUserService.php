<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\{
    UserDoesNotExistException,
    UserId,
};

class GetUserService extends UserService
{
    /**
     * @param GetUserRequest $request
     * @return mixed
     * @throws UserDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new UserId($request->getId());
        $user = $this->getNonNullUser($id);
        return $this->userDataTransformer->write($user)->read();
    }
}
