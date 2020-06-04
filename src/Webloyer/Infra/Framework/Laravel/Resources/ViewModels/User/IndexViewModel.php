<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    public $users;

    public function __construct(LengthAwarePaginator $users)
    {
        $this->users = $users;
    }

    public function users(): LengthAwarePaginator
    {
        return $this->users;
    }
}
