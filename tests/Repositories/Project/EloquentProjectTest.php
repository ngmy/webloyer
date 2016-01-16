<?php

use App\Repositories\Project\EloquentProject;

use Tests\Helpers\Factory;

class EloquentProjectTest extends TestCase {

	protected $useDatabase = true;

	public function test_Should_GetProjectById()
	{
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);

		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);

		$foundProject = $projectRepository->byId($arrangedProject->id);

		$this->assertEquals('Project 1', $foundProject->name);
		$this->assertEquals($arrangedProject->server_id, $foundProject->server_id);
		$this->assertEquals('staging', $foundProject->stage);
	}

	public function test_Should_GetProjectsByPage()
	{
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		Factory::createList('App\Models\Project', [
			['name' => 'Project 1', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
			['name' => 'Project 2', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
			['name' => 'Project 3', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
			['name' => 'Project 4', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
			['name' => 'Project 5', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
		]);

		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);

		$foundProjects = $projectRepository->byPage();

		$this->assertCount(5, $foundProjects->items());
	}

	public function test_Should_CreateNewProject()
	{
		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);

		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$returnedProject = $projectRepository->create([
			'name'      => 'Project 1',
			'recipe_id' => [$arrangedRecipe->id],
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);

		$project = new App\Models\Project;
		$createdProject = $project->find($returnedProject->id);

		$this->assertEquals('Project 1', $createdProject->name);
		$this->assertEquals($arrangedRecipe->id, $createdProject->recipes->first()->id);
		$this->assertEquals($arrangedServer->id, $createdProject->server_id);
		$this->assertEquals('staging', $createdProject->stage);

		$updatedProjectRecipes = $createdProject->recipes;

		$this->assertEquals($arrangedRecipe->id, $updatedProjectRecipes[0]->pivot->recipe_id);
	}

	public function test_Should_UpdateExistingProject()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);
		$arrangedProject->recipes()->sync([
			$arrangedRecipe->id => [
				'recipe_order' => 1,
			]
		]);

		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);
		$arrangedRecipe2 = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 2',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer2 = Factory::create('App\Models\Server', [
			'name'        => 'Server 2 ',
			'description' => '',
			'body'        => '',
		]);
		$projectRepository->update([
			'id'        => $arrangedProject->id,
			'name'      => 'Project 2',
			'recipe_id' => [$arrangedRecipe2->id],
			'server_id' => $arrangedServer2->id,
			'stage'     => 'production',
		]);

		$project = new App\Models\Project;
		$updatedProject = $project->find($arrangedProject->id);

		$this->assertEquals('Project 2', $updatedProject->name);
		$this->assertEquals($arrangedRecipe2->id, $updatedProject->recipes->first()->id);
		$this->assertEquals($arrangedServer2->id, $updatedProject->server_id);
		$this->assertEquals('production', $updatedProject->stage);

		$updatedProjectRecipes = $updatedProject->recipes;

		$this->assertEquals($arrangedRecipe2->id, $updatedProjectRecipes[0]->pivot->recipe_id);
	}

	public function test_Should_DeleteExistingProject()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);
		$arrangedProject->recipes()->sync([
			$arrangedRecipe->id => [
				'recipe_order' => 1,
			]
		]);

		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);
		$projectRepository->delete($arrangedProject->id);

		$project = new App\Models\Project;
		$deletedProject = $project->find($arrangedProject->id);

		$this->assertNull($deletedProject);

		$updatedProjectRecipes = $arrangedProject->recipes;

		$this->assertEmpty($updatedProjectRecipes);
	}

}
