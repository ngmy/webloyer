<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

class Users
{
    /** @var array<int, User> */
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
     * @return array<int, User>
     */
    public function toArray(): array
    {
        return $this->users;
    }
}
