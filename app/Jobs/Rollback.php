<?php

namespace App\Jobs;

use App\Repositories\Project\ProjectInterface;
use App\Repositories\Server\ServerInterface;
use App\Repositories\Setting\SettingInterface;
use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerServerListFileBuilder;
use App\Services\Notification\NotifierInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Deployment;
use Symfony\Component\Process\Process;

/**
 * Class Rollback
 * @package App\Jobs
 */
class Rollback extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Deployment
     */
    protected Deployment $deployment;

    /**
     * @var string
     */
    protected string $executable;

    /**
     * Rollback constructor.
     * @param Deployment $deployment
     */
    public function __construct(Deployment $deployment)
    {
        $this->deployment = $deployment;
        $this->executable = base_path(self::DEP_BASE_PATH);
    }

    /**
     * @param ProjectInterface $projectRepository
     * @param ServerInterface $serverRepository
     * @param NotifierInterface $notifier
     * @param SettingInterface $settingRepository
     * @throws BindingResolutionException
     */
    public function handle(
        ProjectInterface $projectRepository,
        ServerInterface $serverRepository,
        NotifierInterface $notifier,
        SettingInterface $settingRepository
    )
    {
        $deployment = $this->deployment;
        $project = $projectRepository->byId($deployment->project_id);
        $server = $serverRepository->byId($project->server_id);

        if ($deployment->status === null && $deployment->number === $project->lastProjectDeployment()->number) {

            $app = app();

            // Append Host configuration
            /**
             * @var DeployerServerListFileBuilder $serverListFileBuilder
             */
            $serverListFileBuilder = $app->make('App\Services\Deployment\DeployerServerListFileBuilder');
            $serverListFileBuilder->setServer($server)
                ->setProject($project);
            $deployerFileDirector = $app->makeWith('App\Services\Deployment\DeployerFileDirector', ['fileBuilder' => $serverListFileBuilder]);
            $serverListFile = $deployerFileDirector->construct();

            // Create recipe files
            foreach ($project->getRecipes() as $i => $recipe) {
                // HACK: If an instance of DeployerRecipeFileBuilder class is not stored in an array, a destructor is called and a recipe file is deleted immediately.
                $recipeFileBuilders[] = $app->make('App\Services\Deployment\DeployerRecipeFileBuilder')->setRecipe($recipe);
                $deployerFileDirector = $app->makeWith('App\Services\Deployment\DeployerFileDirector', ['fileBuilder' => $recipeFileBuilders[$i]]);
                $recipeFiles[] = $deployerFileDirector->construct();
            }

            $envVariables = $this->setEnviromentVariables($project, $server);

            // Create a deployment file
            /**
             * @var DeployerDeploymentFileBuilder $deploymentFileBuilder
             */
            $deploymentFileBuilder = $app->make('App\Services\Deployment\DeployerDeploymentFileBuilder');
            $deploymentFileBuilder->setProject($project)
                ->setServerListFile($serverListFile)
                ->setRecipeFile($recipeFiles);
            $deployerFileDirector = $app->makeWith('App\Services\Deployment\DeployerFileDirector', ['fileBuilder' => $deploymentFileBuilder]);
            $deploymentFile = $deployerFileDirector->construct();

            // Create a command
            $process = new Process([
                $this->executable,
                "--file={$deploymentFile->getFullPath()}",
                '--ansi',
                '-n',
                'rollback'
            ], null, $envVariables);

            // Run the command
            $tmp['id'] = $deployment->id;
            $tmp['message'] = '';

            $process->setTimeout(1800);
            $process->run(function ($type, $buffer) use (&$tmp, $project, $deployment) {
                $tmp['message'] .= $buffer;
                $tmp['number'] = $deployment->number;

                $project->updateDeployment($tmp);
            });

            // Store the result
            if ($process->isSuccessful()) {
                $tmp['message'] .= $process->getOutput();
            } else {
                $tmp['message'] .= $process->getErrorOutput();
            }

            $data['id'] = $deployment->id;
            $data['number'] = $deployment->number;
            $data['message'] = $tmp['message'];
            $data['status'] = $process->getExitCode();

            $project->updateDeployment($data);
            $this->notify($settingRepository, $project, $deployment, $process, $notifier);
        }
    }
}
