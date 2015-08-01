<?php

use App\Services\Deployment\DeployerServerListFileBuilder;

use Tests\Helpers\Factory;

class DeployerServerListFileBuilderTest extends TestCase {

	use \Tests\Helpers\MockeryHelper;

	protected $mockServerRepository;

	public function setUp()
	{
		parent::setUp();

		$this->mockServerRepository = $this->mock('App\Repositories\Server\ServerInterface');
	}

	public function test_Should_BuildDeployerServerListFile()
	{
		$project = Factory::build('App\Models\Project', [
			'id'         => 1,
			'name'       => 'Project 1',
			'recipe_id ' => 1,
			'server_id'  => 1,
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
		$server = Factory::build('App\Models\Server', [
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

		$this->mockServerRepository
			->shouldReceive('byId')
			->once()
			->andReturn($server);

		$deploymentFileBuilder = new DeployerServerListFileBuilder(
			$this->mockServerRepository
		);
		$result = $deploymentFileBuilder
			->setDeployment($deployment)
			->setProject($project)
			->build()
			->getFilePath();

		$this->assertEquals(storage_path('app/servers_1_10.yml'), $result);
	}

}
