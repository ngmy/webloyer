<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentProjectRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Project as EloquentProject;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Recipe as EloquentRecipe;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Server as EloquentServer;
use Tests\Helpers\EloquentFactory;
use TestCase;

class EloquentProjectRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetProjectOfId()
    {
        $recipe = $this->createRecipe([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => $recipe->name(),
            'description' => $recipe->description(),
            'body'        => $recipe->body(),
            'created_at'  => $recipe->createdAt(),
            'updated_at'  => $recipe->updatedAt(),
        ]);

        $server = $this->createServer([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'name'        => $server->name(),
            'description' => $server->description(),
            'body'        => $server->body(),
            'created_at'  => $server->createdAt(),
            'updated_at'  => $server->updatedAt(),
        ]);

        $project = $this->createProject([
            'recipeIds' => [$createdEloquentRecipe->id],
            'serverId'  => $createdEloquentServer->id,
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentProject = EloquentFactory::create(EloquentProject::class, [
            'server_id'   => $project->serverId()->id(),
            'attributes'  => $project->attribute(),
            'name'        => $project->name(),
            'created_at'  => $project->createdAt(),
            'updated_at'  => $project->updatedAt(),
        ]);

        $eloquentProjectRepository = $this->createEloquentProjectRepository();
        $expectedResult = $eloquentProjectRepository->toEntity($createdEloquentProject);

        $actualResult = $eloquentProjectRepository->projectOfId($expectedResult->projectId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllProjects()
    {
        $recipe = $this->createRecipe([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => $recipe->name(),
            'description' => $recipe->description(),
            'body'        => $recipe->body(),
            'created_at'  => $recipe->createdAt(),
            'updated_at'  => $recipe->updatedAt(),
        ]);

        $server = $this->createServer([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'name'        => $server->name(),
            'description' => $server->description(),
            'body'        => $server->body(),
            'created_at'  => $server->createdAt(),
            'updated_at'  => $server->updatedAt(),
        ]);
        $projects = [
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 1',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 2',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 3',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 4',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 5',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
        ];
        $page = 1;
        $limit = 10;

        $createdEloquentProjects = EloquentFactory::createList(EloquentProject::class, array_map(function ($project) {
            return [
                'server_id'   => $project->serverId()->id(),
                'attributes'  => $project->attribute(),
                'name'        => $project->name(),
                'created_at'  => $project->createdAt(),
                'updated_at'  => $project->updatedAt(),
            ];
        }, $projects));

        $eloquentProjectRepository = $this->createEloquentProjectRepository();

        $expectedResult = (new Collection(array_map(function ($eloquentProject) use ($eloquentProjectRepository) {
                return $eloquentProjectRepository->toEntity($eloquentProject);
            }, $createdEloquentProjects)))->all();

        $actualResult = $eloquentProjectRepository->allProjects();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetProjectsOfPage()
    {
        $recipe = $this->createRecipe([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => $recipe->name(),
            'description' => $recipe->description(),
            'body'        => $recipe->body(),
            'created_at'  => $recipe->createdAt(),
            'updated_at'  => $recipe->updatedAt(),
        ]);

        $server = $this->createServer([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'name'        => $server->name(),
            'description' => $server->description(),
            'body'        => $server->body(),
            'created_at'  => $server->createdAt(),
            'updated_at'  => $server->updatedAt(),
        ]);

        $projects = [
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 1',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 2',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 3',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 4',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createProject([
                'recipeIds' => [$createdEloquentRecipe->id],
                'serverId'  => $createdEloquentServer->id,
                'name'      => 'Project 5',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
        ];
        $page = 1;
        $limit = 10;

        $createdEloquentProjects = EloquentFactory::createList(EloquentProject::class, array_map(function ($project) {
            return [
                'server_id'   => $project->serverId()->id(),
                'attributes'  => $project->attribute(),
                'name'        => $project->name(),
                'created_at'  => $project->createdAt(),
                'updated_at'  => $project->updatedAt(),
            ];
        }, $projects));

        $eloquentProjectRepository = $this->createEloquentProjectRepository();

        $createdProjects = new Collection(array_map(function ($eloquentProject) use ($eloquentProjectRepository) {
                return $eloquentProjectRepository->toEntity($eloquentProject);
            }, $createdEloquentProjects));

        $expectedResult = new LengthAwarePaginator(
            $createdProjects,
            $createdProjects->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );

        $actualResult = $eloquentProjectRepository->projectsOfPage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewProject()
    {
        $recipe = $this->createRecipe([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => $recipe->name(),
            'description' => $recipe->description(),
            'body'        => $recipe->body(),
            'created_at'  => $recipe->createdAt(),
            'updated_at'  => $recipe->updatedAt(),
        ]);

        $server = $this->createServer([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'name'        => $server->name(),
            'description' => $server->description(),
            'body'        => $server->body(),
            'created_at'  => $server->createdAt(),
            'updated_at'  => $server->updatedAt(),
        ]);

        $newProject = $this->createProject([
            'recipeIds' => [$createdEloquentRecipe->id],
            'serverId'  => $createdEloquentServer->id,
            'name'      => 'some name',
        ]);
        $eloquentProjectRepository = $this->createEloquentProjectRepository();

        $returnedProject = $eloquentProjectRepository->save($newProject);

        $createdEloquentProject = EloquentProject::find($returnedProject->ProjectId()->id());

        $this->assertEquals($newProject->name(), $createdEloquentProject->name);

        $this->assertEquals($newProject->name(), $returnedProject->name());

        $this->assertEquals($createdEloquentProject->created_at, $returnedProject->createdAt());
        $this->assertEquals($createdEloquentProject->updated_at, $returnedProject->createdAt());
    }

    public function test_Should_UpdateExistingProject()
    {
        $recipe = $this->createRecipe([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => $recipe->name(),
            'description' => $recipe->description(),
            'body'        => $recipe->body(),
            'created_at'  => $recipe->createdAt(),
            'updated_at'  => $recipe->updatedAt(),
        ]);

        $server = $this->createServer([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'name'        => $server->name(),
            'description' => $server->description(),
            'body'        => $server->body(),
            'created_at'  => $server->createdAt(),
            'updated_at'  => $server->updatedAt(),
        ]);
        $eloquentProjectShouldBeUpdated = EloquentFactory::create(EloquentProject::class, [
            'server_id' => $createdEloquentServer->id,
            'name'      => 'some name 1',
        ]);

        $eloquentProjectRepository = $this->createEloquentProjectRepository();

        $newProject = $this->createProject([
            'recipeIds' => [$createdEloquentRecipe->id],
            'serverId'  => $createdEloquentServer->id,
            'projectId' => $eloquentProjectShouldBeUpdated->id,
            'name'      => 'some name 2',
        ]);

        $returnedProject = $eloquentProjectRepository->save($newProject);

        $updatedEloquentProject = EloquentProject::find($eloquentProjectShouldBeUpdated->id);

        $this->assertEquals($newProject->name(), $updatedEloquentProject->name);

        $this->assertEquals($newProject->name(), $returnedProject->name());

        $this->assertEquals($updatedEloquentProject->created_at, $returnedProject->createdAt());
        $this->assertEquals($updatedEloquentProject->updated_at, $returnedProject->createdAt());
    }

    public function test_Should_DeleteExistingProject()
    {
        $recipe = $this->createRecipe([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => $recipe->name(),
            'description' => $recipe->description(),
            'body'        => $recipe->body(),
            'created_at'  => $recipe->createdAt(),
            'updated_at'  => $recipe->updatedAt(),
        ]);

        $server = $this->createServer([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentServer = EloquentFactory::create(EloquentServer::class, [
            'name'        => $server->name(),
            'description' => $server->description(),
            'body'        => $server->body(),
            'created_at'  => $server->createdAt(),
            'updated_at'  => $server->updatedAt(),
        ]);

        $eloquentProjectShouldBeDeleted = EloquentFactory::create(EloquentProject::class, [
            'server_id'  => $createdEloquentServer->id,
            'attributes' => new ProjectAttribute(''),
            'name'       => 'some name',
        ]);

        $eloquentProjectRepository = $this->createEloquentProjectRepository();

        $eloquentProjectRepository->remove($eloquentProjectRepository->toEntity($eloquentProjectShouldBeDeleted));

        $deletedEloquentProject = EloquentProject::find($eloquentProjectShouldBeDeleted->id);

        $this->assertNull($deletedEloquentProject);
    }

    private function createProject(array $params = [])
    {
        $projectId = null;
        $name = '';
        $recipeIds = [1];
        $serverId = 1;
        $repositoryUrl = '';
        $stage = '';
        $attribute = [
            'deployPath' => ''
        ];
        $emailNotificationRecipient = '';
        $daysToKeepDeployments = 1;
        $maxNumberOfDeploymentsToKeep = 1;
        $keepLastDeployment = 0;
        $githubWebhookSecret = '';
        $githubWebhookExecuteUserId = null;
        $createdAt = '';
        $updatedAt = '';

        extract($params);

        return new Project(
            new ProjectId($projectId),
            $name,
            array_map(function ($recipeId) {
                return new RecipeId($recipeId);
            }, $recipeIds),
            new ServerId($serverId),
            $repositoryUrl,
            $stage,
            new ProjectAttribute($attribute['deployPath']),
            $emailNotificationRecipient,
            $daysToKeepDeployments,
            $maxNumberOfDeploymentsToKeep,
            new KeepLastDeployment($keepLastDeployment),
            $githubWebhookSecret,
            new UserId($githubWebhookExecuteUserId),
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }

    private function createRecipe(array $params = [])
    {
        $recipeId = null;
        $name = '';
        $description = '';
        $body = '';
        $afferentProjectIds = [];
        $createdAt = null;
        $updatedAt = null;

        extract($params);

        return new Recipe(
            new RecipeId($recipeId),
            $name,
            $description,
            $body,
            array_map(function ($projectId) {
                return new ProjectId($projectId);
            }, $afferentProjectIds),
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }

    private function createServer(array $params = [])
    {
        $serverId = null;
        $name = '';
        $description = '';
        $body = '';
        $createdAt = null;
        $updatedAt = null;

        extract($params);

        return new Server(
            new ServerId($serverId),
            $name,
            $description,
            $body,
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }

    private function createEloquentProjectRepository(array $params = [])
    {
        extract($params);

        return new EloquentProjectRepository(new EloquentProject());
    }
}
