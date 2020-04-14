<?php

declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\Project;

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
use Tests\TestCase;

class ProjectControllerTest extends TestCase
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
        $user->shouldReceive('hasPermission')
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

    public function testShouldDisplayIndexPageWhenIndexPageIsRequested()
    {
        $project = $this->mockProjectModel
            ->shouldReceive('getLastDeployment')
            ->times(2)
            ->andReturn([])
            ->mock();
        $project->id = 1;
        $project->name = '';

        $projects = [
            $project,
        ];

        $perPage = 10;

        $this->mockProjectRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($projects, $perPage));

        $response = $this->get('projects');

        $response->assertStatus(200);
        $response->assertViewHas('projects');
    }

    public function testShouldDisplayCreatePageWhenCreatePageIsRequested()
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

        $response = $this->get('projects/create');

        $response->assertStatus(200);
    }

    public function testShouldRedirectToIndexPageWhenStoreProcessSucceeds()
    {
        $this->mockProjectForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $response = $this->post('projects');

        $response->assertRedirect('projects');
    }

    public function testShouldRedirectToCreatePageWhenStoreProcessFails()
    {
        $this->mockProjectForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockProjectForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->post('projects');

        $response->assertRedirect('projects/create');
        $response->assertSessionHasErrors();
    }

    public function testShouldDisplayShowPageWhenShowPageIsRequestedAndResourceIsFound()
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
        $project->id = 1;
        $project->name = '';

        $server = factory(Server::class)->make();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($server);

        $response = $this->get('projects/1');

        $response->assertStatus(200);
        $response->assertViewHas('project');
    }

    public function testShouldDisplayNotFoundPageWhenShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('projects/1');

        $response->assertStatus(404);
    }

    public function testShouldDisplayEditPageWhenEditPageIsRequestedAndResourceIsFound()
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
        $project->id = 1;
        $project->name = '';

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

        $response = $this->get('projects/1/edit');

        $response->assertStatus(200);
        $response->assertViewHas('project');
    }

    public function testShouldDisplayNotFoundPageWhenEditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('projects/1/edit');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenUpdateProcessSucceeds()
    {
        $project = factory(Project::class)->make();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockProjectForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $response = $this->put('projects/1');

        $response->assertRedirect('projects');
    }

    public function testShouldRedirectToEditPageWhenUpdateProcessFails()
    {
        $project = factory(Project::class)->make([
            'id' => 1,
            'name' => '',
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

        $response = $this->put('projects/1');

        $response->assertRedirect('projects/1/edit');
        $response->assertSessionHasErrors();
    }

    public function testShouldDisplayNotFoundPageWhenUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('projects/1');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenDestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $project = factory(Project::class)->make([
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

        $response = $this->delete('projects/1');

        $response->assertRedirect('projects');
    }

    public function testShouldDisplayNotFoundPageWhenDestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->delete('projects/1');

        $response->assertStatus(404);
    }
}
