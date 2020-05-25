<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\User;

use Webloyer\Domain\Model\User\{
    User,
    Users,
};

class UsersDtoDataTransformer implements UsersDataTransformer
{
    /** @var Users */
    private $users;
    /** @var UserDtoDataTransformer */
    private $userDataTransformer;

    public function __construct(UserDtoDataTransformer $userDataTransformer)
    {
        $this->userDataTransformer = $userDataTransformer;
    }

    /**
     * @param Users $users
     * @return self
     */
    public function write(Users $users): self
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return array<int, object>
     */
    public function read()
    {
        return array_map(function (User $user): object {
            return $this->userDataTransformer->write($user)->read();
        }, $this->users->toArray());
    }
}
