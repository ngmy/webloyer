<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    private $projects;

    public function __construct(LengthAwarePaginator $projects)
    {
        $this->projects = $projects;
    }

    public function projects(): LengthAwarePaginator
    {
        return $this->projects;
    }
}
