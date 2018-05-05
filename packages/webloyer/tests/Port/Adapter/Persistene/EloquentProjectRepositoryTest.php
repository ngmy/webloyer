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
        $createdEloquentServer = EloquentFactory::create(EloquentServer::class);
        $createdEloquentProject = EloquentFactory::create(EloquentProject::class, [
            'server_id'  => $createdEloquentServer->id,
            'attributes' => new ProjectAttribute('deploy_path'),
            'created_at' => '2018-04-30 12:00:00',
            'updated_at' => '2018-04-30 12:00:00',
        ]);
        $expectedResult = $createdEloquentProject->toEntity();

        $actualResult = $this->createEloquentProjectRepository()->projectOfId($expectedResult->projectId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllProjects()
    {
        $createdEloquentServer = EloquentFactory::create(EloquentServer::class);
        $createdEloquentProjects = EloquentFactory::createList(EloquentProject::class, [
            [
                'server_id'  => $createdEloquentServer->id,
                'attributes' => new ProjectAttribute('deploy_path'),
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'server_id'  => $createdEloquentServer->id,
                'attributes' => new ProjectAttribute('deploy_path'),
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'server_id'  => $createdEloquentServer->id,
                'attributes' => new ProjectAttribute('deploy_path'),
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $expectedResult = (new Collection(array_map(function ($eloquentProject) {
            return $eloquentProject->toEntity();
        }, $createdEloquentProjects)))->all();

        $actualResult = $this->createEloquentProjectRepository()->allProjects();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetProjectsOfPage()
    {
        $createdEloquentServer = EloquentFactory::create(EloquentServer::class);
        $createdEloquentProjects = EloquentFactory::createList(EloquentProject::class, [
            [
                'server_id'  => $createdEloquentServer->id,
                'attributes' => new ProjectAttribute('deploy_path'),
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'server_id'  => $createdEloquentServer->id,
                'attributes' => new ProjectAttribute('deploy_path'),
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'server_id'  => $createdEloquentServer->id,
                'attributes' => new ProjectAttribute('deploy_path'),
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $createdProjects = new Collection(array_map(function ($eloquentProject) {
            return $eloquentProject->toEntity();
        }, $createdEloquentProjects));
        $page = 1;
        $limit = 10;
        $expectedResult = new LengthAwarePaginator(
            $createdProjects,
            $createdProjects->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );

        $actualResult = $this->createEloquentProjectRepository()->projectsOfPage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewProject()
    {
        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class);
        $createdEloquentServer = EloquentFactory::create(EloquentServer::class);
        $newProject = $this->createProject([
            'recipeIds' => [$createdEloquentRecipe->id],
            'serverId'  => $createdEloquentServer->id,
        ]);

        $returnedProject = $this->createEloquentProjectRepository()->save($newProject);

        $createdEloquentProject = EloquentProject::find($returnedProject->ProjectId()->id());

        $this->assertEquals($newProject->name(), $createdEloquentProject->name);

        $this->assertEquals($newProject->name(), $returnedProject->name());

        $this->assertEquals($createdEloquentProject->created_at, $returnedProject->createdAt());
        $this->assertEquals($createdEloquentProject->updated_at, $returnedProject->updatedAt());
    }

    public function test_Should_UpdateExistingProject()
    {
        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class);
        $createdEloquentServer = EloquentFactory::create(EloquentServer::class);
        $eloquentProjectShouldBeUpdated = EloquentFactory::create(EloquentProject::class, [
            'server_id' => $createdEloquentServer->id,
        ]);
        $newProject = $this->createProject([
            'recipeIds' => [$createdEloquentRecipe->id],
            'serverId'  => $createdEloquentServer->id,
            'projectId' => $eloquentProjectShouldBeUpdated->id,
            'name'      => 'new name',
        ]);

        $returnedProject = $this->createEloquentProjectRepository()->save($newProject);

        $updatedEloquentProject = EloquentProject::find($eloquentProjectShouldBeUpdated->id);

        $this->assertEquals($newProject->name(), $updatedEloquentProject->name);

        $this->assertEquals($newProject->name(), $returnedProject->name());

        $this->assertEquals($updatedEloquentProject->created_at, $returnedProject->createdAt());
        $this->assertEquals($updatedEloquentProject->updated_at, $returnedProject->updatedAt());
    }

    public function test_Should_DeleteExistingProject()
    {
        $createdEloquentServer = EloquentFactory::create(EloquentServer::class);
        $eloquentProjectShouldBeDeleted = EloquentFactory::create(EloquentProject::class, [
            'server_id'  => $createdEloquentServer->id,
            'attributes' => new ProjectAttribute('deploy_path'),
        ]);

        $this->createEloquentProjectRepository()->remove($eloquentProjectShouldBeDeleted->toEntity());

        $deletedEloquentProject = EloquentProject::find($eloquentProjectShouldBeDeleted->id);

        $this->assertNull($deletedEloquentProject);
    }

    private function createProject(array $params = [])
    {
        $projectId = null;
        $name = '';
        $recipeIds = [];
        $serverId = null;
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

    private function createEloquentProjectRepository(array $params = [])
    {
        extract($params);

        return new EloquentProjectRepository(new EloquentProject());
    }
}
