<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class ShowViewModel extends ViewModel
{
    /** @var object */
    private $project;

    /**
     * @param object $project
     * @return void
     */
    public function __construct(object $project)
    {
        $this->project = $project;
    }

    /**
     * @return object
     */
    public function project(): object
    {
        return $this->project;
    }

    /**
     * @param object $project
     * @return string
     */
    public function projectGitHubWebhookUserEmail(object $project): string
    {
        return $project->gitHubWebhookUser ? $project->gitHubWebhookUser->email : '';
    }

    /**
     * @param bool $value
     * @return string
     */
    public function yesOrNo(bool $value): string
    {
        return $value ? 'yes' : 'no';
    }

    /**
     * @param string|null $value
     * @return string
     */
    public function hyphenIfBlank(?string $value): string
    {
        return empty($value) ? '-' : $value;
    }
}
