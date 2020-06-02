<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    public $servers;

    public function __construct(LengthAwarePaginator $servers)
    {
        $this->servers = $servers;
    }

    public function servers(): LengthAwarePaginator
    {
        return $this->servers;
    }
}
