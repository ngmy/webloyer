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
            return '<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>';
        } elseif ($deployment->status == 'failed') {
            return '<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>';
        } else {
            return '<span></span>';
        }
    }
}
