<?php

use App\Repositories\Project\EloquentProject;

use Tests\Helpers\Factory;

class EloquentProjectTest extends TestCase {

	protected $useDatabase = true;

	public function test_Should_GetProjectById()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'stage'     => 'staging',
		]);

		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);

		$foundProject = $projectRepository->byId($arrangedProject->id);

		$this->assertEquals('Project 1', $foundProject->name);
		$this->assertEquals($arrangedProject->id, $foundProject->recipe_id);
		$this->assertEquals('staging', $foundProject->stage);
	}

	public function test_Should_GetProjectsByPage()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		Factory::createList('App\Models\Project', [
			['name' => 'Project 1', 'recipe_id' => $arrangedRecipe->id, 'stage' => 'staging'],
			['name' => 'Project 2', 'recipe_id' => $arrangedRecipe->id, 'stage' => 'staging'],
			['name' => 'Project 3', 'recipe_id' => $arrangedRecipe->id, 'stage' => 'staging'],
			['name' => 'Project 4', 'recipe_id' => $arrangedRecipe->id, 'stage' => 'staging'],
			['name' => 'Project 5', 'recipe_id' => $arrangedRecipe->id, 'stage' => 'staging'],
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
		$returnedProject = $projectRepository->create([
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'stage'     => 'staging',
		]);

		$project = new App\Models\Project;
		$createdProject = $project->find($returnedProject->id);

		$this->assertEquals('Project 1', $createdProject->name);
		$this->assertEquals($arrangedRecipe->id, $createdProject->recipe_id);
		$this->assertEquals('staging', $createdProject->stage);
	}

	public function test_Should_UpdateExistingProject()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'stage'     => 'staging',
		]);

		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);
		$arrangedRecipe2 = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$projectRepository->update([
			'id'          => $arrangedProject->id,
			'name'        => 'Project 2',
			'recipe_id'   => $arrangedRecipe2->id,
			'stage'       => 'production',
		]);

		$project = new App\Models\Project;
		$updatedProject = $project->find($arrangedProject->id);

		$this->assertEquals('Project 2', $updatedProject->name);
		$this->assertEquals($arrangedRecipe2->id, $updatedProject->recipe_id);
		$this->assertEquals('production', $updatedProject->stage);
	}

	public function test_Should_DeleteExistingProject()
	{
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'stage'     => 'staging',
		]);

		$projectRepository = new EloquentProject(
			new App\Models\Project,
			new App\Models\MaxDeployment
		);
		$projectRepository->delete($arrangedProject->id);

		$project = new App\Models\Project;
		$deletedProject = $project->find($arrangedProject->id);

		$this->assertNull($deletedProject);
	}

}
