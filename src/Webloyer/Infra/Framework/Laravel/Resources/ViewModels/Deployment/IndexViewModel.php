<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Deployment;

use Collective\Html\HtmlFacade as Html;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\ViewModelHelpers;

class IndexViewModel extends ViewModel
{
    use ViewModelHelpers;

    /** @var LengthAwarePaginator<object> */
    private $deployments;
    /** @var string */
    private $projectId;

    /**
     * @param LengthAwarePaginator<object> $deployments
     * @param string                       $projectId
     * @return void
     */
    public function __construct(LengthAwarePaginator $deployments, string $projectId)
    {
        $this->deployments = $deployments;
        $this->projectId = $projectId;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function deployments(): LengthAwarePaginator
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
     * @return array<string, string>
     */
    public function deploymentStatus(): array
    {
        return [
            'succeeded' => '<i class="fa fa-check-circle fa-lg fa-fw" aria-hidden="true" style="color: green;"></i> succeeded',
            'failed' => '<i class="fa fa-exclamation-circle fa-lg fa-fw" aria-hidden="true" style="color: red;"></i> failed',
            'running' => '<i class="fa fa-refresh fa-spin fa-lg fa-fw" aria-hidden="true" style="color: blue;"></i> running',
            'queued' => '<i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true" style="color: gray;"></i> queued',
        ];
    }

    /**
     * @return list<string>
     */
    public function deploymentUserEmailOf(): array
    {
        return array_reduce($this->deployments->toArray()['data'], function (array $carry, object $deployment): array {
            $carry[$deployment->number] = $this->hyphenIfBlank($deployment->user->email);
            return $carry;
        }, []);
    }

    /**
     * @return list<string>
     */
    public function deploymentLinks(): array
    {
        return array_reduce($this->deployments->toArray()['data'], function (array $carry, object $deployment): array {
            $link = Html::linkRoute('projects.deployments.show', 'Show', [$this->projectId, $deployment->number], ['class' => 'btn btn-default']);
            $carry[$deployment->number] = $link->toHtml();
            return $carry;
        }, []);
    }

    /**
     * @return list<string>
     */
    public function deploymentApiUrls(): array
    {
        return array_reduce($this->deployments->toArray()['data'], function (array $carry, object $deployment): array {
            $carry[$deployment->number] = route('projects.deployments.show', [$this->projectId, $deployment->number]);
            return $carry;
        }, []);
    }
}
