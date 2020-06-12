<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var LengthAwarePaginator<object> */
    private $users;

    /**
     * @param LengthAwarePaginator<object> $users
     * @return void
     */
    public function __construct(LengthAwarePaginator $users)
    {
        $this->users = $users;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function users(): LengthAwarePaginator
    {
        return $this->users;
    }
}
