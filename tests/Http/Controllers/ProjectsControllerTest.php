<?php

use Tests\Helpers\Factory;

class ProjectsControllerTest extends TestCase {

	use Tests\Helpers\ControllerTestHelper;

	use Tests\Helpers\MockeryHelper;

	protected $mockProjectRepository;

	protected $mockProjectForm;

	protected $mockRecipeRepository;

	public function setUp()
	{
		parent::setUp();

		Session::start();

		$this->auth();

		$this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
		$this->mockProjectForm = $this->mock('App\Services\Form\Project\ProjectForm');
		$this->mockRecipeRepository = $this->mock('App\Repositories\Recipe\RecipeInterface');
	}

	public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
	{
		$projects = Factory::buildList('App\Models\Project', [
			['id' => 1, 'name' => 'Project 1', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
			['id' => 2, 'name' => 'Project 2', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
			['id' => 3, 'name' => 'Project 3', 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon],
		]);

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

	public function test_Should_RedirectToEditPage_When_ShowPageIsRequestedAndResourceIsFound()
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

		$this->get('projects/1');

		$this->assertRedirectedToRoute('projects.edit', [$project]);
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

		$this->mockRecipeRepository
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
