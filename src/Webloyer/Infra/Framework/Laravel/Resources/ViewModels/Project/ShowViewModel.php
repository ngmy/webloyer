<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class ShowViewModel extends ViewModel
{
    private $project;

    public function __construct(object $project)
    {
        $this->project = $project;
    }

    public function project(): object
    {
        return $this->project;
    }

    public function projectGitHubWebhookUserEmail(object $project): string
    {
        return $project->gitHubWebhookUser ? $project->gitHubWebhookUser->email : '';
    }

    public function yesOrNo(bool $value): string
    {
        return $value ? 'yes' : 'no';
    }

    public function hyphenIfBlank(?string $value): string
    {
        return empty($value) ? '-' : $value;
    }
}
