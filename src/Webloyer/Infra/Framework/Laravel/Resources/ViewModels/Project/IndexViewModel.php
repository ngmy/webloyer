<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var LengthAwarePaginator<object> */
    private $projects;

    /**
     * @param LengthAwarePaginator<object> $projects
     * @return void
     */
    public function __construct(LengthAwarePaginator $projects)
    {
        $this->projects = $projects;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function projects(): LengthAwarePaginator
    {
        return $this->projects;
    }

    /**
     * @return array<string, string>
     */
    public function projectLastDeploymentStatusIconOf(): array
    {
        return array_reduce($this->projects->toArray()['data'], function (array $carry, object $project): array {
            $carry[$project->id] = isset($project->lastDeployment)
                ? $this->convertProjectLastDeploymentStatusToIcon($project->lastDeployment->status)
                : '';
            return $carry;
        }, []);
    }

    /**
     * @return array<string, string>
     */
    public function projectLastDeploymentOf(): array
    {
        return array_reduce($this->projects->toArray()['data'], function (array $carry, object $project): array {
            assert(isset($project->id));
            $carry[$project->id] = isset($project->lastDeployment)
                ? $project->lastDeployment->finishDate .
                ' ' .
                '(' .
                link_to_route('projects.deployments.show', $project->lastDeployment->number, [$project->id,  $project->lastDeployment->number]) .
                ')'
                : '';
            return $carry;
        }, []);
    }

    /**
     * @param string $projectLastDeploymentStatus
     * @return string
     */
    private function convertProjectLastDeploymentStatusToIcon(string $projectLastDeploymentStatus): string
    {
        switch ($projectLastDeploymentStatus) {
            case 'succeeded':
                return '<i class="fa fa-check-circle fa-lg fa-fw" aria-hidden="true" style="color: green;"></i>';
            case 'failed':
                return '<i class="fa fa-exclamation-circle fa-lg fa-fw" aria-hidden="true" style="color: red;"></i>';
            case 'running':
                return '<i class="fa fa-refresh fa-spin fa-lg fa-fw" aria-hidden="true" style="color: blue;"></i>';
            case 'queued':
                return '<i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true" style="color: gray;"></i>';
            default:
                return '';
        }
    }
}
