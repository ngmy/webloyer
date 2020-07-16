<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApplySettings;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId as IdentityAccessUserId;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm\ProjectForm;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\MockeryHelper;
use TestCase;

class ProjectsControllerTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    private $projectForm;

    private $projectService;

    private $deploymentService;

    private $recipeService;

    private $serverService;

    private $userService;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->mock(User::class);
        $user->shouldReceive('can')->andReturn(true);
        $user->shouldReceive('name');
        $this->auth($user);

        $this->projectForm = $this->mock(ProjectForm::class);
        $this->projectService = $this->mock(ProjectService::class);
        $this->deploymentService = $this->mock(DeploymentService::class);
        $this->recipeService = $this->mock(RecipeService::class);
        $this->serverService = $this->mock(ServerService::class);
        $this->userService = $this->mock(UserService::class);

        $this->app->instance(ProjectForm::class, $this->projectForm);
        $this->app->instance(ProjectService::class, $this->projectService);
        $this->app->instance(DeploymentService::class, $this->deploymentService);
        $this->app->instance(RecipeService::class, $this->recipeService);
        $this->app->instance(ServerService::class, $this->serverService);
        $this->app->instance(UserService::class, $this->userService);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $project = $this->createProject();
        $projects = new Collection([
            $project,
        ]);
        $page = 1;
        $perPage = 10;

        $this->projectService
            ->shouldReceive('getProjectsByPage')
            ->with($page, $perPage)
            ->andReturn(
                new LengthAwarePaginator(
                    $projects,
                    $projects->count(),
                    $perPage,
                    $page,
                    [
                        'path' => Paginator::resolveCurrentPath(),
                    ]
                )
            )
            ->once();

        foreach ($projects as $project) {
            $deployment = $this->createDeployment([
                'projectId' => $project->projectId()->id(),
            ]);
            $this->deploymentService
                ->shouldReceive('getLastDeployment')
                ->with($project->projectId()->id())
                ->andReturn($deployment)
                ->once();
        }

        $response = $this->get('projects');

        $response->assertStatus(200);
        $response->assertViewHas('projects');
        $response->assertViewHas('lastDeployments');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $recipe = $this->createRecipe();
        $server = $this->createServer();
        $user = $this->createUser();
        $recipes = [
            $recipe,
        ];
        $servers = [
            $server,
        ];
        $users = [
            $user,
        ];

        $this->recipeService
            ->shouldReceive('getAllRecipes')
            ->withNoArgs()
            ->andReturn($recipes)
            ->once();
        $this->serverService
            ->shouldReceive('getAllServers')
            ->withNoArgs()
            ->andReturn($servers)
            ->once();
        $this->userService
            ->shouldReceive('getAllUsers')
            ->withNoArgs()
            ->andReturn($users)
            ->once();

        $response = $this->get('projects/create');

        $response->assertStatus(200);
        $response->assertViewHas('recipes');
        $response->assertViewHas('servers');
        $response->assertViewHas('users');
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->projectForm
            ->shouldReceive('save')
            ->andReturn(true)
            ->once();

        $response = $this->post('projects');

        $response->assertRedirect('projects');
    }

    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
    {
        $this->projectForm
            ->shouldReceive('save')
            ->andReturn(false)
            ->once();

        $this->projectForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->post('projects');

        $response->assertRedirect('projects/create');
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        foreach ($project->recipeIds() as $recipeId) {
            $recipe = $this->createRecipe([
                'recipeId' => $recipeId->id(),
            ]);
            $this->recipeService
                ->shouldReceive('getRecipeById')
                ->with($recipe->recipeId()->id())
                ->andReturn($recipe)
                ->once();
        }

        $this->serverService
            ->shouldReceive('getServerById')
            ->with($project->serverId()->id())
            ->andReturn($this->createServer())
            ->once();

        $response = $this->get("projects/{$project->projectId()->id()}");

        $response->assertStatus(200);
        $response->assertViewHas('project');
        $response->assertViewHas('projectRecipe');
        $response->assertViewHas('projectServer');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("projects/{$project->projectId()->id()}");

        $response->assertStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $project = $this->createProject();
        $recipe = $this->createRecipe();
        $server = $this->createServer();
        $user = $this->createUser();
        $recipes = [
            $recipe,
        ];
        $servers = [
            $server,
        ];
        $users = [
            $user,
        ];

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        foreach ($project->recipeIds() as $recipeId) {
            $recipe = $this->createRecipe([
                'recipeId' => $recipeId->id(),
            ]);
            $this->recipeService
                ->shouldReceive('getRecipeById')
                ->with($recipe->recipeId()->id())
                ->andReturn($recipe)
                ->once();
        }

        $this->recipeService
            ->shouldReceive('getAllRecipes')
            ->withNoArgs()
            ->andReturn($recipes)
            ->once();
        $this->serverService
            ->shouldReceive('getAllServers')
            ->withNoArgs()
            ->andReturn($servers)
            ->once();
        $this->userService
            ->shouldReceive('getAllUsers')
            ->withNoArgs()
            ->andReturn($users)
            ->once();

        $response = $this->get("projects/{$project->projectId()->id()}/edit");

        $response->assertStatus(200);
        $response->assertViewHas('project');
        $response->assertViewHas('recipes');
        $response->assertViewHas('servers');
        $response->assertViewHas('projectRecipe');
        $response->assertViewHas('users');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("projects/{$project->projectId()->id()}/edit");

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->projectForm
            ->shouldReceive('update')
            ->andReturn(true)
            ->once();

        $response = $this->put("projects/{$project->projectId()->id()}");

        $response->assertRedirect('projects');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->projectForm
            ->shouldReceive('update')
            ->andReturn(false)
            ->once();

        $this->projectForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->put("projects/{$project->projectId()->id()}");

        $response->assertRedirect("projects/{$project->projectId()->id()}/edit");
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->put("projects/{$project->projectId()->id()}");

        $response->assertStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->projectService
            ->shouldReceive('removeProject')
            ->with($project->projectId()->id())
            ->once();

        $response = $this->delete("projects/{$project->projectId()->id()}");

        $response->assertRedirect('projects');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->delete("projects/{$project->projectId()->id()}");

        $response->assertStatus(404);
    }

    private function createProject(array $params = [])
    {
        $projectId = 1;
        $name = '';
        $recipeIds = [1];
        $serverId = 1;
        $repositoryUrl = '';
        $stage = '';
        $attribute = [
            'deployPath' => '',
        ];
        $emailNotificationRecipient = '';
        $daysToKeepDeployments = 1;
        $maxNumberOfDeploymentsToKeep = 1;
        $keepLastDeployment = 1;
        $githubWebhookSecret = '';
        $githubWebhookExecuteUserId = 1;
        $createdAt = null;
        $updatedAt = null;
        $concurrencyVersion = '';

        extract($params);

        $project = $this->mock(Project::class);

        $project->shouldReceive('projectId')->andReturn(new ProjectId($projectId));
        $project->shouldReceive('name')->andReturn($name);
        $project->shouldReceive('recipeIds')->andReturn(array_map(function ($recipeId) {
            return new RecipeId($recipeId);
        }, $recipeIds));
        $project->shouldReceive('serverId')->andReturn(new ServerId($serverId));
        $project->shouldReceive('repositoryUrl')->andReturn($repositoryUrl);
        $project->shouldReceive('stage')->andReturn($stage);
        $project->shouldReceive('attribute')->andReturn(new ProjectAttribute($attribute['deployPath']));
        $project->shouldReceive('emailNotificationRecipient')->andReturn($emailNotificationRecipient);
        $project->shouldReceive('daysToKeepDeployments')->andReturn($daysToKeepDeployments);
        $project->shouldReceive('maxNumberOfDeploymentsToKeep')->andReturn($maxNumberOfDeploymentsToKeep);
        $project->shouldReceive('keepLastDeployment')->andReturn(new KeepLastDeployment($keepLastDeployment));
        $project->shouldReceive('githubWebhookSecret')->andReturn($githubWebhookSecret);
        $project->shouldReceive('githubWebhookExecuteUserId')->andReturn(new UserId($githubWebhookExecuteUserId));
        $project->shouldReceive('createdAt')->andReturn(new Carbon($createdAt));
        $project->shouldReceive('updatedAt')->andReturn(new Carbon($updatedAt));
        $project->shouldReceive('concurrencyVersion')->andReturn($concurrencyVersion);

        return $project;
    }

    private function createDeployment(array $params = [])
    {
        $projectId = 1;
        $deploymentId = 1;
        $task = 'deploy';
        $status = 2;
        $message = null;
        $deployedUserId = 1;
        $createdAt = null;
        $updatedAt = null;

        extract($params);

        $deployment = $this->mock(Deployment::class);

        $deployment->shouldReceive('projectId')->andReturn(new ProjectId($projectId));
        $deployment->shouldReceive('deploymentId')->andReturn(new DeploymentId($deploymentId));
        $deployment->shouldReceive('task')->andReturn(new Task($task));
        $deployment->shouldReceive('status')->andReturn(new Status($status));
        $deployment->shouldReceive('message')->andReturn($message);
        $deployment->shouldReceive('deployedUserId')->andReturn(new UserId($deployedUserId));
        $deployment->shouldReceive('createdAt')->andReturn(new Carbon($createdAt));
        $deployment->shouldReceive('updatedAt')->andReturn(new Carbon($updatedAt));

        return $deployment;
    }

    private function createRecipe(array $params = [])
    {
        $recipeId = 1;
        $name = '';
        $description = '';
        $body = '';
        $afferentProjectIds = [1];
        $createdAt = null;
        $updatedAt = null;
        $concurrencyVersion = '';

        extract($params);

        $recipe = $this->mock(Recipe::class);

        $recipe->shouldReceive('recipeId')->andReturn(new RecipeId($recipeId));
        $recipe->shouldReceive('name')->andReturn($name);
        $recipe->shouldReceive('description')->andReturn($description);
        $recipe->shouldReceive('body')->andReturn($body);
        $recipe->shouldReceive('afferentProjectIds')->andReturn(array_map(function ($afferentProjectId) {
            return new ProjectId($afferentProjectId);
        }, $afferentProjectIds));
        $recipe->shouldReceive('afferentProjectsCount')->andReturn(count($afferentProjectIds));
        $recipe->shouldReceive('createdAt')->andReturn(new Carbon($createdAt));
        $recipe->shouldReceive('updatedAt')->andReturn(new Carbon($updatedAt));
        $recipe->shouldReceive('concurrencyVersion')->andReturn($concurrencyVersion);

        return $recipe;
    }

    private function createServer(array $params = [])
    {
        $serverId = 1;
        $name = '';
        $description = '';
        $body = '';
        $createdAt = null;
        $updatedAt = null;
        $concurrencyVersion = '';

        extract($params);

        $server = $this->mock(Server::class);

        $server->shouldReceive('serverId')->andReturn(new ServerId($serverId));
        $server->shouldReceive('name')->andReturn($name);
        $server->shouldReceive('description')->andReturn($description);
        $server->shouldReceive('body')->andReturn($body);
        $server->shouldReceive('createdAt')->andReturn(new Carbon($createdAt));
        $server->shouldReceive('updatedAt')->andReturn(new Carbon($updatedAt));
        $server->shouldReceive('concurrencyVersion')->andReturn($concurrencyVersion);

        return $server;
    }

    private function createUser(array $params = [])
    {
        $userId = 1;
        $name = '';
        $email = '';

        extract($params);

        $user = $this->mock(User::class);

        $user->shouldReceive('userId')->andReturn(new IdentityAccessUserId($userId));
        $user->shouldReceive('name')->andReturn($name);
        $user->shouldReceive('email')->andReturn($email);

        return $user;
    }
}
