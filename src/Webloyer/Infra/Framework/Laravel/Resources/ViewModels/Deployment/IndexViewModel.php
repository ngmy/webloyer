<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Deployment;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    private $deployments;
    private $projectId;

    public function __construct(LengthAwarePaginator $deployments, string $projectId)
    {
        $this->deployments = $deployments;
        $this->projectId = $projectId;
    }

    public function deployments(): LengthAwarePaginator
    {
        return $this->deployments;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function deploymentStatus(object $deployment): string
    {
        if ($deployment->status == 'succeeded') {
            return '<i class="fa fa-check-circle fa-lg fa-fw" aria-hidden="true" style="color: green;"></i> ' . $deployment->status;
        } elseif ($deployment->status == 'failed') {
            return '<i class="fa fa-exclamation-circle fa-lg fa-fw" aria-hidden="true" style="color: red;"></i> ' . $deployment->status;
        } elseif ($deployment->status == 'running') {
            return '<i class="fa fa-refresh fa-spin fa-lg fa-fw" aria-hidden="true" style="color: blue;"></i> ' . $deployment->status;
        } elseif ($deployment->status == 'queued') {
            return '<i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true" style="color: gray;"></i> ' . $deployment->status;
        } else {
            return '';
        }
    }
}
