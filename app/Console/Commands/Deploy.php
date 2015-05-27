<?php namespace App\Console\Commands;

use App\Repositories\Project\ProjectInterface;
use App\Repositories\Deployment\DeploymentInterface;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\ProcessBuilder;

use Storage;

class Deploy extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'webloyer:deploy';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deploy application.';

	protected $project;

	protected $deployment;

	protected $processBuilder;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(ProjectInterface $project, DeploymentInterface $deployment, ProcessBuilder $processBuilder)
	{
		parent::__construct();

		$this->project        = $project;
		$this->deployment     = $deployment;
		$this->processBuilder = $processBuilder;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$deploymentId = $this->argument('deployment-id');

		$deployment = $this->deployment->byId($deploymentId);
		$project    = $this->project->byId($deployment->project_id);

		$recipeFile = $project->recipe_path;
		$stage      = $project->stage;

		// Create a command
		$this->processBuilder
			->add('dep')
			->add("-f=$recipeFile")
			->add('-vv')
			->add('deploy');

		if (isset($stage)) {
			$this->processBuilder->add($stage);
		}

		// Run the command
		$tmp['id']      = $deploymentId;
		$tmp['message'] = '';

		$process = $this->processBuilder->getProcess();

		$process->run(function ($type, $buffer) use (&$tmp)
		{
			$tmp['message'] .= $buffer;

			$this->deployment->update($tmp);
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

		$this->deployment->update($data);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['deployment-id', InputArgument::REQUIRED, 'A deployment ID.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
		];
	}

}
