<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\UserRoleSpecification;

class GetAllRolesService extends UserService
{
    /**
     * @return mixed
     */
    public function execute($request = null)
    {
        return UserRoleSpecification::slugs();
    }
}
