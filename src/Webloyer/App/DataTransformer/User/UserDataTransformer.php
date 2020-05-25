<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\User;

use Webloyer\Domain\Model\User\User;

/**
 * @codeCoverageIgnore
 */
interface UserDataTransformer
{
    /**
     * @param User $user
     * @return self
     */
    public function write(User $user): self;
    /**
     * @return mixed
     */
    public function read();
}
