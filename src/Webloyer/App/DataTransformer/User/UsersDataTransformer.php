<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\User;

use Webloyer\Domain\Model\User\Users;

/**
 * @codeCoverageIgnore
 */
interface UsersDataTransformer
{
    /**
     * @param Users $users
     * @return self
     */
    public function write(Users $users): self;
    /**
     * @return mixed
     */
    public function read();
}
