<?php

use App\Services\Deployment\DeployerDeploymentFileBuilder;

use Tests\Helpers\Factory;

class DeployerDeploymentFileBuilderTest extends TestCase {

	use \Tests\Helpers\MockeryHelper;

	protected $mockRecipeRepository;

	public function setUp()
	{
		parent::setUp();

		$this->mockRecipeRepository = $this->mock('App\Repositories\Recipe\RecipeInterface');
	}

	public function test_Should_BuildDeployerDeploymentFile()
	{
		$project = Factory::build('App\Models\Project', [
			'id'         => 1,
			'name'       => 'Project 1',
			'recipe_id ' => 1,
			'servers'    => 'servers.yml',
			'repository' => 'http://example.com',
			'stage'      => 'staging',
		]);
		$deployment = Factory::build('App\Models\Deployment', [
			'id'         => 1,
			'project_id' => 1,
			'number'     => 10,
			'task'       => 'deploy',
			'user_id'    => 1,
			'created_at' => new Carbon\Carbon,
			'updated_at' => new Carbon\Carbon,
			'user'       => new App\Models\User,
		]);
		$recipe = Factory::build('App\Models\Recipe', [
			'id'         => 1,
			'name'       => '',
			'desctipton' => '',
			'body'       => '',
		]);

		Storage::shouldReceive('delete')
			->once()
			->andReturn(1);

		Storage::shouldReceive('put')
			->once()
			->andReturn(1);

		$this->mockRecipeRepository
			->shouldReceive('byId')
			->once()
			->andReturn($recipe);

		$deploymentFileBuilder = new DeployerDeploymentFileBuilder(
			$this->mockRecipeRepository
		);
		$result = $deploymentFileBuilder
			->setDeployment($deployment)
			->setProject($project)
			->build()
			->getFilePath();

		$this->assertEquals(storage_path('app/deploy_1_10.php'), $result);
	}

}
