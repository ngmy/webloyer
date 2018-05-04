<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Project as EloquentProject;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    private $eloquentProject;

    /**
     * Create a new repository instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Project $eloquentProject
     * @return void
     */
    public function __construct(EloquentProject $eloquentProject)
    {
        $this->eloquentProject = $eloquentProject;
    }

    public function allProjects()
    {
        $eloquentProjects = $this->eloquentProject
            ->orderBy('name')
            ->get();

        $projects = $eloquentProjects
            ->map(function ($eloquentProject, $key) {
                return $eloquentProject->toEntity();
            })->all();

        return $projects;
    }

    public function projectsOfPage($page = 1, $limit = 10)
    {
        $eloquentProjects = $this->eloquentProject
            ->orderBy('name')
            ->get();

        $projects = $eloquentProjects
            ->slice($limit * ($page - 1), $limit)
            ->map(function ($eloquentProject, $key) {
                return $eloquentProject->toEntity();
            });

        return new LengthAwarePaginator(
            $projects,
            $eloquentProjects->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );
    }

    public function projectOfId(ProjectId $projectId)
    {
        $primaryKey = $projectId->id();

        $eloquentProject = $this->eloquentProject->find($primaryKey);

        $project = $eloquentProject->toEntity();

        return $project;
    }

    public function remove(Project $project)
    {
        $eloquentProject = $this->toEloquent($project);

        $eloquentProject->delete();

        return true;
    }

    public function save(Project $project)
    {
        $eloquentProject = $this->toEloquent($project);

        $eloquentProject->save();
        $recipeIds = [];
        foreach ($project->recipeIds() as $recipeId) {
            $recipeIds[] = $recipeId->id();
        }
        $eloquentProject->syncRecipes($recipeIds);

        $project = $eloquentProject->toEntity();

        return $project;
    }

    public function toEloquent(Project $project)
    {
        $primaryKey = $project->projectId()->id();

        if (is_null($primaryKey)) {
            $eloquentProject = new EloquentProject();
        } else {
            $eloquentProject = $this->eloquentProject->find($primaryKey);
        }

        $eloquentProject->name = $project->name();
        $eloquentProject->stage = $project->stage();
        $eloquentProject->server_id = $project->serverId()->id();
        $eloquentProject->repository = $project->repositoryUrl();
        $eloquentProject->attributes = $project->attribute();
        $eloquentProject->email_notification_recipient = $project->emailNotificationRecipient();
        $eloquentProject->days_to_keep_deployments = $project->daysToKeepDeployments();
        $eloquentProject->max_number_of_deployments_to_keep = $project->maxNumberOfDeploymentsToKeep();
        $eloquentProject->keep_last_deployment = $project->keepLastDeployment()->value();
        $eloquentProject->github_webhook_secret = $project->githubWebhookSecret();
        $eloquentProject->github_webhook_user_id = $project->githubWebhookExecuteUserId()->id();

        return $eloquentProject;
    }
}
