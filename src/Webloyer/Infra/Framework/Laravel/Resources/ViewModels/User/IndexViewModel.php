<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var list<object> */
    private $users;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param list<object> $users
     * @return void
     */
    public function __construct(array $users)
    {
        $this->users = $users;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function users(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            array_slice(
                $this->users,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($this->users),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
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
}
