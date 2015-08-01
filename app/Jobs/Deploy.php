<?php namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\Deployment\DeploymentInterface;
use App\Repositories\Project\ProjectInterface;
use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerServerListFileBuilder;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

use Symfony\Component\Process\ProcessBuilder;

class Deploy extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	protected $deployment;

	protected $executable;

	/**
	 * Create a new job instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $deployment
	 * @return void
	 */
	public function __construct(Model $deployment)
	{
		$this->deployment = $deployment;
		$this->executable = base_path('vendor/bin/dep');
	}

	/**
	 * Execute the job.
	 *
	 * @param \App\Repositories\Deployment\DeployCommanderInterface  $deploymentRepository
	 * @param \App\Repositories\Project\ProjectInterface             $projectRepository
	 * @param \Symfony\Component\Process\ProcessBuilder              $processBuilder
	 * @param \App\Services\Deployment\DeployerDeploymentFileBuilder $deploymentFileBuilder
	 * @param \App\Services\ServerList\DeployerServerListFileBuilder $serverListFileBuilder
	 * @return void
	 */
	public function handle(DeploymentInterface $deploymentRepository, ProjectInterface $projectRepository, ProcessBuilder $processBuilder, DeployerDeploymentFileBuilder $deploymentFileBuilder, DeployerServerListFileBuilder $serverListFileBuilder)
	{
		$deploymentId = $this->deployment->id;

		$deployment = $deploymentRepository->byId($deploymentId);
		$project    = $projectRepository->byId($deployment->project_id);

		$stage = $project->stage;

		// Create a server list file
		$serverListFile = $serverListFileBuilder
			->setDeployment($deployment)
			->setProject($project)
			->build()
			->getFilePath();

		// Create a deployment file
		$deploymentFile = $deploymentFileBuilder
			->setDeployment($deployment)
			->setProject($project)
			->setServerListFile($serverListFile)
			->build()
			->getFilePath();

		// Create a command
		$processBuilder
			->add($this->executable)
			->add("-f=$deploymentFile")
			->add('-n')
			->add('-vv')
			->add('deploy')
			->add($stage);

		// Run the command
		$tmp['id']      = $deploymentId;
		$tmp['message'] = '';

		$process = $processBuilder->getProcess();
		$process->setTimeout(600);
		$process->run(function ($type, $buffer) use (&$tmp, $deploymentRepository)
		{
			$tmp['message'] .= $buffer;

			$deploymentRepository->update($tmp);
		});

		// Store the result
		if ($process->isSuccessful()) {
			$message = $process->getOutput();
		} else {
			$message = $process->getErrorOutput();
		}

		$data['id']      = $deploymentId;
		$data['message'] = $message;
		$data['status']  = $process->getExitCode();

		$deploymentRepository->update($data);
	}

}
