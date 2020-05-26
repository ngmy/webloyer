<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\UserId;

class GetUserService extends UserService
{
    /**
     * @param GetUserRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $id = new UserId($request->getId());
        $user = $this->getNonNullUser($id);
        return $this->userDataTransformer->write($user)->read();
    }
}
