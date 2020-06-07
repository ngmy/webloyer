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

    public function deploymentStatus(): array
    {
        return [
            'succeeded' => '<i class="fa fa-check-circle fa-lg fa-fw" aria-hidden="true" style="color: green;"></i>',
            'failed' => '<i class="fa fa-exclamation-circle fa-lg fa-fw" aria-hidden="true" style="color: red;"></i>',
            'running' => '<i class="fa fa-refresh fa-spin fa-lg fa-fw" aria-hidden="true" style="color: blue;"></i>',
            'queued' => '<i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true" style="color: gray;"></i>',
        ];
    }
}
