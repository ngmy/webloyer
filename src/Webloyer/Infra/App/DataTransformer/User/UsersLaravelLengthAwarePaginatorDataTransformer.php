<?php

declare(strict_types=1);

namespace Webloyer\Infra\App\DataTransformer\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Webloyer\App\DataTransformer\User\{
    UserDataTransformer,
    UsersDataTransformer,
    UsersDtoDataTransformer,
};
use Webloyer\Domain\Model\User\Users;

class UsersLaravelLengthAwarePaginatorDataTransformer implements UsersDataTransformer
{
    /** @var Users */
    private $users;
    /** @var UsersDtoDataTransformer */
    private $usersDataTransformer;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param UsersDtoDataTransformer $usersDataTransformer
     * @return void
     */
    public function __construct(UsersDtoDataTransformer $usersDataTransformer)
    {
        $this->usersDataTransformer = $usersDataTransformer;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @param int $perPage
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
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
     * @return LengthAwarePaginator<object>
     */
    public function read()
    {
        $users = $this->usersDataTransformer->write($this->users)->read();
        return new LengthAwarePaginator(
            array_slice(
                $users,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($users),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
    }

    /**
     * @return UserDataTransformer
     */
    public function userDataTransformer(): UserDataTransformer
    {
        return $this->usersDataTransformer->userDataTransformer();
    }
}
