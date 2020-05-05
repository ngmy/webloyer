<?php

declare(strict_types=1);

namespace Webloyer\Infra\Messaging;

use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Storage;
use Symfony\Component\Process\Process;
use Webloyer\Domain\Model\Deployment;

class RunDeployerWhenDeploymentWasStartedEventListener implements ShouldQueue
{
    /** @var Deployment\DeploymentRepository */
    private $deploymentRepository;

    private $createdFiles = [];

    /**
     * Create the event listener.
     *
     * @param Deployment\DeploymentRepository $deploymentRepository
     * @return void
     */
    public function __construct(Deployment\DeploymentRepository $deploymentRepository)
    {
        $this->deploymentRepository = $deploymentRepository;
    }

    public function __destruct()
    {
        if (empty($this->createdFiles)) {
            return;
        }
        foreach ($this->createdFiles as $createdFile) {
            $this->deleteFile($createdFile);
        }
    }

    /**
     * Handle the event.
     *
     * @param Deployment\DeploymentWasStartedEvent $event
     * @return void
     */
    public function handle(Deployment\DeploymentWasStartedEvent $event): void
    {
        $this->createDeployerFile($event);
        $this->runDeployer($event);
    }

    /**
     * @param Deployment\Deployment $deployment
     * @return void
     */
    public function createDeployerFile(Deployment\DeploymentWasStartedEvent $event): void
    {
        // Create recipe files
        foreach ($this->getRecipeFileNames() as $recipeFileName) {
            $this->createFile($recipeFileName, $event->recipe()->body());
        }

        // Create the server file
        $this->createFile($this->getServerFileName(), $event->server()->body());

        // Create the deployer file
        $contents[] = '<?php';
        $contents[] = 'namespace Deployer;';
        foreach ($this->getRecipeFileNames() as $recipeFileName) {
            $contents[] = "require '" . $recipeFileName . "';";
        }
        $contents[] = "set('repository', '" . $event->project()->repository() . "');";
        $contents[] = "serverList('" . $this->getServerFileName() . "');";
        $this->createFile($this->getDeployerFileName(), implode(PHP_EOL, $contents));
    }

    public function runDeployer(Deployment\DeploymentWasStartedEvent $event): void
    {
        // Create the deployer process
        $process = new Process([
            base_path('vendor/bin/dep'),
            '-f=' . $this->getDeployerFileName(),
            '--ansi',
            '-n',
            '-vvv',
            $event->deployment()->task(),
            $event->project()->stage(),
        ]);
        $process->setTimeout(600);

        // Run the deployer process and update the deployment log
        $deployment = $event->deployment();
        $process->run(function (string $type, string $buffer) use ($deployment) {
            DB::trancation(function () use ($deployment) {
                $deployment->appendLog($buffer);
                $this->deploymentRepository->save($deployment);
            });
        });

        // Update the deployment log and status
        DB::transaction(function () use ($process, $deployment) {
            $log = $process->isSuccessful() ? $process->getOutput() : $process->getErrorOutput();
            $status = $process->getExitCode();
            $deployment->changeLog($log)
                ->changeStatus($status)
                ->changeFinishDate('now')
                ->finish(
                    $event->project(),
                    $event->recipes(),
                    $event->server(),
                    $event->executor()
                );
            $this->deploymentRepository->save($deployment);
        });
    }

    public function getServerFileName(Deployment\DeploymentWasStartedEvent $event): string
    {
        return sprintf('server_%s_%s_%s.yaml', $event->deployment()->projectId(), $event->deployment()->number());
    }

    public function getRecipeFileNames(Deployment\DeploymentWasStartedEvent $event): array
    {
        $i = 1;
        return array_map(function (Recipe\Recipe $recipe) use ($event, $i): string {
            return $this->getRecipeFileName($event, $i);
        }, $event->recipes()->toArray());
    }

    public function getRecipeFileName(Deployment\DeploymentWasStartedEvent $event, int $i): string
    {
        return sprintf('server_%s_%s_%s.php', $event->deployment()->projectId(), $event->deployment()->number(), $i++);
    }

    public function getDeployerFileName(Deployment\DeploymentWasStartedEvent $event): string
    {
        return sprintf('deployer_%s_%s.php', $event->deployment()->projectId(), $event->deployment()->number());
    }

    public function createFile(string $fileName, string $contents): void
    {
        Storage::disk('local')->put($fileName, $contents);
        $this->createdFiles[] = $fileName;
    }

    public function deleteFile(string $fileName): void
    {
        Storage::disk('local')->delete($fileName);
    }
}
