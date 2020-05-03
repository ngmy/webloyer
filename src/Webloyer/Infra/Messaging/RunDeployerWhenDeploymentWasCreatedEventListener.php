<?php

declare(strict_types=1);

namespace Webloyer\Infra\Messaging;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Storage;
use Symfony\Component\Process\Process;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Domain\Model\Project;

class RunDeployerWhenDeploymentWasCreatedEventListener implements ShouldQueue
{
    /** @var Deployment\DeploymentRepository */
    private $deploymentRepository;
    /** @var Project\ProjectRepository */
    private $projectRepository;

    /**
     * Create the event listener.
     *
     * @param Project\ProjectRepository       $projectRepository
     * @param Deployment\DeploymentRepository $deploymentRepository
     * @return void
     */
    public function __construct(
        Project\ProjectRepositoryt $projectRepository,
        Deployment\DeploymentRepository $deploymentRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->deploymentRepository = $deploymentRepository;
    }

    /**
     * Handle the event.
     *
     * @param Deployment\DeploymentWasCreatedEvent $event
     * @return void
     */
    public function handle(Deployment\DeploymentWasCreatedEvent $event): void
    {
        $deployment = $this->deploymentRepository->findById(new Deployment\DeploymentNumber($event->number()));
        $project = $this->projectRepository->findById(new Project\ProjectId($event->projecId()));

        $deployerFileName = sprintf('%s_%s.php', $event->projectId(), $event->number());
        $task = $event->task();
        $stage = $project->stage();

        try {
            // Create the deployer process
            $process = new Process([
                base_path('vendor/bin/dep'),
                '-f=' . $deployerFileName,
                '--ansi',
                '-n',
                '-vvv',
                $task,
                $stage,
            ]);
            $process->setTimeout(600);

            // Run the deployer process and update the deployment log
            $log = '';
            $process->run(function (string $type, string $buffer) use (&$log, $deployment) {
                $log .= $buffer;
                $deployment->changeLog($log);
                $this->deploymentRepository->save($deployment);
            });

            // Update the deployment log and status
            $log = $process->isSuccessful() ? $process->getOutput() : $process->getErrorOutput();
            $status = $process->getExitCode();
            $deployment->changeLog($log);
            $deployment->changeStatus($status);
            $this->deploymentRepository->save($deployment);

            // TODO Notify
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->deleteFile($deployerFileName);
        }
    }

    public function deleteFile(string $fileName): void
    {
        Storage::disk('local')->delete($fileName);
    }
}
