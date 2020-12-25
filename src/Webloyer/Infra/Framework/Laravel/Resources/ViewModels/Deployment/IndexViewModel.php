<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Deployment;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\ViewModelHelpers;

class IndexViewModel extends ViewModel
{
    use ViewModelHelpers;

    /** @var list<object> */
    private $deployments;
    /** @var string */
    private $projectId;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param list<object> $deployments
     * @param string       $projectId
     * @return void
     */
    public function __construct(array $deployments, string $projectId)
    {
        $this->deployments = $deployments;
        $this->projectId = $projectId;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @return list<object>
     */
    public function deployments(): array
    {
        return $this->deployments;
    }

    /**
     * @return string
     */
    public function projectId(): string
    {
        return $this->projectId;
    }

    /**
     * @return array<int, string>
     */
    public function deploymentStatusIconOf(): array
    {
        return array_reduce($this->deployments, function (array $carry, object $deployment): array {
            $carry[$deployment->number] = $this->convertDeploymentStatusToIcon($deployment->status);
            return $carry;
        }, []);
    }

    /**
     * @return array<int, string>
     */
    public function deploymentUserEmailOf(): array
    {
        return array_reduce($this->deployments, function (array $carry, object $deployment): array {
            $carry[$deployment->number] = $this->hyphenIfBlank($deployment->user->email);
            return $carry;
        }, []);
    }

    /**
     * @return array<int, string>
     */
    public function deploymentShowLinkOf(): array
    {
        return array_reduce($this->deployments, function (array $carry, object $deployment): array {
            $link = link_to_route('projects.deployments.show', 'Show', [$this->projectId, $deployment->number], ['class' => 'btn btn-default']);
            $carry[$deployment->number] = $link->toHtml();
            return $carry;
        }, []);
    }

    /**
     * @return string
     */
    public function deploymentIndexApiUrl(): string
    {
        return route('projects.deployments.index', [$this->projectId]) . '?' . http_build_query(['page' => $this->currentPage]);
    }

    /**
     * @return string
     */
    public function deploymentPaginationLink(): string
    {
        return (new LengthAwarePaginator(
            array_slice(
                $this->deployments,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($this->deployments),
            $this->perPage,
            $this->currentPage,
            $this->options
        ))
            ->links()
            ->toHtml();
    }

    /**
     * @param string $deploymentStatus
     * @return string
     */
    private function convertDeploymentStatusToIcon(string $deploymentStatus): string
    {
        switch ($deploymentStatus) {
            case 'succeeded':
                return '<i class="fa fa-check-circle fa-lg fa-fw" aria-hidden="true" style="color: green;"></i> succeeded';
            case 'failed':
                return '<i class="fa fa-exclamation-circle fa-lg fa-fw" aria-hidden="true" style="color: red;"></i> failed';
            case 'running':
                return '<i class="fa fa-refresh fa-spin fa-lg fa-fw" aria-hidden="true" style="color: blue;"></i> running';
            case 'queued':
                return '<i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true" style="color: gray;"></i> queued';
            default:
                /** @phpstan-ignore-next-line */
                assert(false);
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
