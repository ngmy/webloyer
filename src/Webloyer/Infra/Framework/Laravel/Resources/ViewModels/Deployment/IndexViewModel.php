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

    public function deploymentStatus(): array
    {
        return [
            'succeeded' => '<i class="fa fa-check-circle fa-lg fa-fw" aria-hidden="true" style="color: green;"></i> succeeded',
            'failed' => '<i class="fa fa-exclamation-circle fa-lg fa-fw" aria-hidden="true" style="color: red;"></i> failed',
            'running' => '<i class="fa fa-refresh fa-spin fa-lg fa-fw" aria-hidden="true" style="color: blue;"></i> running',
            'queued' => '<i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true" style="color: gray;"></i> queued',
        ];
    }

    public function deploymentLinks(): array
    {
        return array_reduce($this->deployments->toArray()['data'], function (array $carry, object $deployment): array {
            $link = link_to_route('projects.deployments.show', 'Show', [$this->projectId, $deployment->number], ['class' => 'btn btn-default']);
            $carry[$deployment->number] = $link->toHtml();
            return $carry;
        }, []);
    }

    public function deploymentApiUrls(): array
    {
        return array_reduce($this->deployments->toArray()['data'], function (array $carry, object $deployment): array {
            $carry[$deployment->number] = route('projects.deployments.show', [$this->projectId, $deployment->number]);
            return $carry;
        }, []);
    }
}
