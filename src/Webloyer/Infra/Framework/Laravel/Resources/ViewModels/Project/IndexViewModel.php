<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var list<object> */
    private $projects;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param list<object> $projects
     * @return void
     */
    public function __construct(array $projects)
    {
        $this->projects = $projects;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function projects(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            array_slice(
                $this->projects,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($this->projects),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
    }

    /**
     * @return array<string, string>
     */
    public function projectLastDeploymentStatusIconOf(): array
    {
        return array_reduce($this->projects, function (array $carry, object $project): array {
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
        return array_reduce($this->projects, function (array $carry, object $project): array {
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
