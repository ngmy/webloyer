<?php

declare(strict_types=1);

namespace Webloyer\Infra\App\DataTransformer\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Webloyer\App\DataTransformer\User\{
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
    private $perPage;
    /** @var int */
    private $currentPage;
    /** @var array */
    private $options;

    public function __construct(UsersDtoDataTransformer $usersDataTransformer)
    {
        $this->usersDataTransformer = $usersDataTransformer;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

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
     * @return Paginator
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
}
