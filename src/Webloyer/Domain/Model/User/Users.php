<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

class Users
{
    /** @var list<User> */
    private $users;

    /**
     * @param User ...$users
     * @return void
     */
    public function __construct(User ...$users)
    {
        $this->users = $users;
    }

    /**
     * @return list<User>
     */
    public function toArray(): array
    {
        return $this->users;
    }
}
