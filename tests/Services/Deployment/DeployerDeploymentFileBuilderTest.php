<?php

use App\Services\Deployment\DeployerDeploymentFileBuilder;

use Tests\Helpers\Factory;

class DeployerDeploymentFileBuilderTest extends TestCase {

	public function test_Should_BuildDeployerDeploymentFile()
	{
		$project = Factory::build('App\Models\Project', [
			'id'          => 1,
			'name'        => 'Project 1',
			'recipe_path' => 'deploy.php',
			'servers'     => 'servers.yml',
			'repository'  => 'http://example.com',
			'stage'       => 'staging',
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

		Storage::shouldReceive('delete')
			->once()
			->andReturn(1);

		Storage::shouldReceive('put')
			->once()
			->andReturn(1);

		$deploymentFileBuilder = new DeployerDeploymentFileBuilder;
		$result = $deploymentFileBuilder
			->setDeployment($deployment)
			->setProject($project)
			->build()
			->getFilePath();

		$this->assertEquals(storage_path('app/deploy_1_10.php'), $result);
	}

}
