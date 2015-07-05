<?php namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\Deployment\DeploymentInterface;
use App\Repositories\Project\ProjectInterface;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

use Symfony\Component\Process\ProcessBuilder;

class Rollback extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	protected $deployment;

	/**
	 * Create a new job instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $deployment
	 * @return void
	 */
	public function __construct(Model $deployment)
	{
		$this->deployment = $deployment;
	}

	/**
	 * Execute the job.
	 *
	 * @param \App\Repositories\Deployment\DeployCommanderInterface $deploymentRepository
	 * @param \App\Repositories\Project\ProjectInterface            $projectRepository
	 * @param \Symfony\Component\Process\ProcessBuilder             $processBuilder
	 * @return void
	 */
	public function handle(DeploymentInterface $deploymentRepository, ProjectInterface $projectRepository, ProcessBuilder $processBuilder)
	{
		$deploymentId = $this->deployment->id;

		$deployment = $deploymentRepository->byId($deploymentId);
		$project    = $projectRepository->byId($deployment->project_id);

		$recipeFile = $project->recipe_path;
		$stage      = $project->stage;

		// Create a command
		$processBuilder
			->add('dep')
			->add("-f=$recipeFile")
			->add('-vv')
			->add('rollback');

		if (isset($stage)) {
			$processBuilder->add($stage);
		}

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
