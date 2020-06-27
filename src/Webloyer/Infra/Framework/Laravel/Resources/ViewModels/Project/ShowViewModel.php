<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\ViewModelHelpers;

class ShowViewModel extends ViewModel
{
    use ViewModelHelpers;

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
     * @return array<object>
     */
    public function projectRecipes(): array
    {
        return empty($this->project->recipes)
            ? [new class {
                /** @var string */
                public $name = '';
            }]
            : $this->project->recipes;
    }

    /**
     * @return int
     */
    public function projectRecipeCount(): int
    {
        return count($this->projectRecipes());
    }

    /**
     * @return string
     */
    public function projectGitHubWebhookUserEmail(): string
    {
        return $this->project->gitHubWebhookUser ? $this->project->gitHubWebhookUser->email : '';
    }
}
