<?php

use Tests\Helpers\Factory;
use Tests\Helpers\DummyMiddleware;

class ProjectsControllerTest extends TestCase
{
    use Tests\Helpers\ControllerTestHelper;

    use Tests\Helpers\MockeryHelper;

    protected $mockProjectRepository;

    protected $mockProjectForm;

    protected $mockRecipeRepository;

    protected $mockServerRepository;

    protected $mockUserRepository;

    protected $mockProjectModel;

    protected $mockProjectAttributeEntity;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(\App\Http\Middleware\ApplySettings::class, new DummyMiddleware);

        Session::start();

        $user = $this->mockPartial('App\Models\User');
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockProjectForm = $this->mock('App\Services\Form\Project\ProjectForm');
        $this->mockRecipeRepository = $this->mock('App\Repositories\Recipe\RecipeInterface');
        $this->mockServerRepository = $this->mock('App\Repositories\Server\ServerInterface');
        $this->mockUserRepository = $this->mock('App\Repositories\User\UserInterface');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
        $this->mockProjectAttributeEntity = $this->mock('App\Entities\ProjectAttribute\ProjectAttributeEntity');
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
            ->andReturn(new Illuminate\Pagination\Paginator($projects, $perPage));

        $this->get('projects');

        $this->assertResponseOk();
        $this->assertViewHas('projects');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $this->mockRecipeRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

        $this->mockServerRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

        $this->mockUserRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

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
            ->andReturn(new Illuminate\Database\Eloquent\Collection);
        $project->shouldReceive('getGithubWebhookUser')
            ->twice()
            ->andReturn(new App\Models\User);
        $project->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockProjectAttributeEntity);

        $server = Factory::build('App\Models\Server', [
            'id'          => 1,
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
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
            ->andReturn(new Illuminate\Database\Eloquent\Collection)
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
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

        $this->mockServerRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

        $this->mockUserRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection);

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
        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
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
        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
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
        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
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
