<?php

use App\Repositories\Project\EloquentProject;

use Tests\Helpers\Factory;

class EloquentProjectTest extends TestCase {

	protected $useDatabase = true;

	public function test_Should_GetProjectById()
	{
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'        => 'Project 1',
			'recipe_path' => 'deploy.php',
			'stage'       => 'staging',
		]);

		$projectRepository = new EloquentProject(new App\Models\Project);

		$foundProject = $projectRepository->byId($arrangedProject->id);

		$this->assertEquals('Project 1', $foundProject->name);
		$this->assertEquals('deploy.php', $foundProject->recipe_path);
		$this->assertEquals('staging', $foundProject->stage);
	}

	public function test_Should_GetProjectsByPage()
	{
		Factory::createList('App\Models\Project', [
			['name' => 'Project 1', 'recipe_path' => 'deploy.php', 'stage' => 'staging'],
			['name' => 'Project 2', 'recipe_path' => 'deploy.php', 'stage' => 'staging'],
			['name' => 'Project 3', 'recipe_path' => 'deploy.php', 'stage' => 'staging'],
			['name' => 'Project 4', 'recipe_path' => 'deploy.php', 'stage' => 'staging'],
			['name' => 'Project 5', 'recipe_path' => 'deploy.php', 'stage' => 'staging'],
		]);

		$projectRepository = new EloquentProject(new App\Models\Project);

		$foundProjects = $projectRepository->byPage();

		$this->assertCount(5, $foundProjects->items());
	}

	public function test_Should_CreateNewProject()
	{
		$projectRepository = new EloquentProject(new App\Models\Project);

		$returnedProject = $projectRepository->create([
			'name'        => 'Project 1',
			'recipe_path' => 'deploy.php',
			'stage'       => 'staging',
		]);

		$project = new App\Models\Project;
		$createdProject = $project->find($returnedProject->id);

		$this->assertEquals('Project 1', $createdProject->name);
		$this->assertEquals('deploy.php', $createdProject->recipe_path);
		$this->assertEquals('staging', $createdProject->stage);
	}

	public function test_Should_UpdateExistingProject()
	{
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'        => 'Project 1',
			'recipe_path' => 'deploy.php',
			'stage'       => 'staging',
		]);

		$projectRepository = new EloquentProject(new App\Models\Project);
		$projectRepository->update([
			'id'          => $arrangedProject->id,
			'name'        => 'Project 2',
			'recipe_path' => 'deploy2.php',
			'stage'       => 'production',
		]);

		$project = new App\Models\Project;
		$updatedProject = $project->find($arrangedProject->id);

		$this->assertEquals('Project 2', $updatedProject->name);
		$this->assertEquals('deploy2.php', $updatedProject->recipe_path);
		$this->assertEquals('production', $updatedProject->stage);
	}

	public function test_Should_DeleteExistingProject()
	{
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'        => 'Project 1',
			'recipe_path' => 'deploy.php',
			'stage'       => 'staging',
		]);

		$projectRepository = new EloquentProject(new App\Models\Project);
		$projectRepository->delete($arrangedProject->id);

		$project = new App\Models\Project;
		$deletedProject = $project->find($arrangedProject->id);

		$this->assertNull($deletedProject);
	}

}
