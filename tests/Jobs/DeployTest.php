<?php namespace Tests\Jobs;

use App\Jobs\Deploy;

use Tests\Helpers\Factory;

class DeployTest extends \TestCase {

	use \Tests\Helpers\MockeryHelper;

	protected $mockProjectRepository;

	protected $mockDeploymentRepository;

	protected $mockProcessBuilder;

	protected $mockProcess;

	protected $mockDeploymentFileBuilder;

	public function setUp()
	{
		parent::setUp();

		$this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
		$this->mockDeploymentRepository = $this->mock('App\Repositories\Deployment\DeploymentInterface');
		$this->mockProcessBuilder = $this->mock('Symfony\Component\Process\ProcessBuilder');
		$this->mockProcess = $this->mockPartial('Symfony\Component\Process\Process');
		$this->mockDeploymentFileBuilder = $this->mock('App\Services\Deployment\DeployerDeploymentFileBuilder');
	}

	public function test_Should_Work_When_DeployerIsNormalEnd()
	{
		$deployment = Factory::build('App\Models\Deployment', [
			'id'         => 1,
			'project_id' => 1,
			'number'     => 1,
			'task'       => 'deploy',
			'user_id'    => 1,
			'created_at' => new \Carbon\Carbon,
			'updated_at' => new \Carbon\Carbon,
			'user'       => new \App\Models\User,
		]);

		$project = Factory::build('App\Models\Project', [
			'id'         => 1,
			'name'       => 'Project 1',
			'recipe_id'  => 1,
			'stage'      => 'staging',
			'created_at' => new \Carbon\Carbon,
			'updated_at' => new \Carbon\Carbon,
		]);

		$this->mockDeploymentRepository
			->shouldReceive('byId')
			->once()
			->andReturn($deployment);

		$this->mockProjectRepository
			->shouldReceive('byId')
			->once()
			->andReturn($project);

		$this->mockDeploymentRepository
			->shouldReceive('update')
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('setDeployment')
			->andReturn($this->mockDeploymentFileBuilder)
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('setProject')
			->andReturn($this->mockDeploymentFileBuilder)
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('build')
			->andReturn($this->mockDeploymentFileBuilder)
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('getFilePath')
			->once();

		$this->mockProcess
			->shouldReceive('run')
			->once();

		$this->mockProcess
			->shouldReceive('isSuccessful')
			->once()
			->andReturn(true);

		$this->mockProcess
			->shouldReceive('getOutput')
			->once();

		$this->mockProcess
			->shouldReceive('getExitCode')
			->once();

		$this->mockProcessBuilder
			->shouldReceive('add')
			->times(6)
			->andReturn($this->mockProcessBuilder);

		$this->mockProcessBuilder
			->shouldReceive('getProcess')
			->once()
			->andReturn($this->mockProcess);

		$job = new Deploy($deployment);

		$job->handle(
			$this->mockDeploymentRepository,
			$this->mockProjectRepository,
			$this->mockProcessBuilder,
			$this->mockDeploymentFileBuilder
		);
	}

	public function test_Should_Work_When_DeployerIsAbnormalEnd()
	{
		$deployment = Factory::build('App\Models\Deployment', [
			'id'         => 1,
			'project_id' => 1,
			'number'     => 1,
			'task'       => 'deploy',
			'user_id'    => 1,
			'created_at' => new \Carbon\Carbon,
			'updated_at' => new \Carbon\Carbon,
			'user'       => new \App\Models\User,
		]);

		$project = Factory::build('App\Models\Project', [
			'id'         => 1,
			'name'       => 'Project 1',
			'recipe_id'  => 1,
			'stage'      => 'staging',
			'created_at' => new \Carbon\Carbon,
			'updated_at' => new \Carbon\Carbon,
		]);

		$this->mockDeploymentRepository
			->shouldReceive('byId')
			->once()
			->andReturn($deployment);

		$this->mockProjectRepository
			->shouldReceive('byId')
			->once()
			->andReturn($project);

		$this->mockDeploymentRepository
			->shouldReceive('update')
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('setDeployment')
			->andReturn($this->mockDeploymentFileBuilder)
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('setProject')
			->andReturn($this->mockDeploymentFileBuilder)
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('build')
			->andReturn($this->mockDeploymentFileBuilder)
			->once();

		$this->mockDeploymentFileBuilder
			->shouldReceive('getFilePath')
			->once();
		$this->mockProcess
			->shouldReceive('run')
			->once();

		$this->mockProcess
			->shouldReceive('isSuccessful')
			->once()
			->andReturn(false);

		$this->mockProcess
			->shouldReceive('getErrorOutput')
			->once();

		$this->mockProcess
			->shouldReceive('getExitCode')
			->once();

		$this->mockProcessBuilder
			->shouldReceive('add')
			->times(6)
			->andReturn($this->mockProcessBuilder);

		$this->mockProcessBuilder
			->shouldReceive('getProcess')
			->once()
			->andReturn($this->mockProcess);

		$job = new Deploy($deployment);

		$job->handle(
			$this->mockDeploymentRepository,
			$this->mockProjectRepository,
			$this->mockProcessBuilder,
			$this->mockDeploymentFileBuilder
		);
	}

}
