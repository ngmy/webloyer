<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Entities\ProjectAttribute\ProjectAttributeEntity;
use App\Http\Middleware\ApplySettings;
use App\Models\Project;
use App\Models\Server;
use App\Models\User;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\Recipe\RecipeInterface;
use App\Repositories\Server\ServerInterface;
use App\Repositories\User\UserInterface;
use App\Services\Form\Project\ProjectForm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\Factory;
use Tests\TestCase;

class ProjectsControllerTest extends TestCase
{
    use ControllerTestHelper;

    protected $mockProjectRepository;

    protected $mockProjectForm;

    protected $mockRecipeRepository;

    protected $mockServerRepository;

    protected $mockUserRepository;

    protected $mockProjectModel;

    protected $mockProjectAttributeEntity;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->partialMock(User::class);
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockProjectRepository = $this->mock(ProjectInterface::class);
        $this->mockProjectForm = $this->mock(ProjectForm::class);
        $this->mockRecipeRepository = $this->mock(RecipeInterface::class);
        $this->mockServerRepository = $this->mock(ServerInterface::class);
        $this->mockUserRepository = $this->mock(UserInterface::class);
        $this->mockProjectModel = $this->partialMock(Project::class);
        $this->mockProjectAttributeEntity = $this->mock(ProjectAttributeEntity::class);
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $project = $this->mockProjectModel
            ->shouldReceive('getLastDeployment')
            ->times(2)
            ->andReturn([])
            ->mock();

        $projects = [
            $project,
        ];

        $perPage = 10;

        $this->mockProjectRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($projects, $perPage));

        $this->get('projects');

        $this->assertResponseOk();
        $this->assertViewHas('projects');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $this->mockRecipeRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $this->mockServerRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $this->mockUserRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $this->get('projects/create');

        $this->assertResponseOk();
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->mockProjectForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->post('projects');

        $this->assertRedirectedToRoute('projects.index');
    }

    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
    {
        $this->mockProjectForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockProjectForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $this->post('projects');

        $this->assertRedirectedToRoute('projects.create');
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $this->mockProjectAttributeEntity
            ->shouldReceive('getDeployPath')
            ->once();

        $project = $this->mockProjectModel;
        $project->shouldReceive('getRecipes')
            ->once()
            ->andReturn(new Collection());
        $project->shouldReceive('getGithubWebhookUser')
            ->twice()
            ->andReturn(new User());
        $project->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockProjectAttributeEntity);

        $server = Factory::build(Server::class, [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $this->get('projects/1');

        $this->assertResponseOk();
        $this->assertViewHas('project');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('projects/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $this->mockProjectAttributeEntity
            ->shouldReceive('getDeployPath')
            ->once();

        $project = $this->mockProjectModel
            ->shouldReceive('getRecipes')
            ->once()
            ->andReturn(new Collection())
            ->mock();
        $project->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockProjectAttributeEntity);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockRecipeRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $this->mockServerRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $this->mockUserRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $this->get('projects/1/edit');

        $this->assertResponseOk();
        $this->assertViewHas('project');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('projects/1/edit');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $project = Factory::build(Project::class, [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon(),
            'updated_at' => new Carbon(),
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockProjectForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $this->put('projects/1');

        $this->assertRedirectedToRoute('projects.index');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $project = Factory::build(Project::class, [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon(),
            'updated_at' => new Carbon(),
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockProjectForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $this->mockProjectForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $this->put('projects/1');

        $this->assertRedirectedToRoute('projects.edit', [$project]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->put('projects/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $project = Factory::build(Project::class, [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon(),
            'updated_at' => new Carbon(),
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockProjectRepository
            ->shouldReceive('delete')
            ->once();

        $this->delete('projects/1');

        $this->assertRedirectedToRoute('projects.index');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->delete('projects/1');

        $this->assertResponseStatus(404);
    }
}
