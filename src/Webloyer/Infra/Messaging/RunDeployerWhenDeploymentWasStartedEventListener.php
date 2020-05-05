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

    /**
     * Handle the event.
     *
     * @param Deployment\DeploymentWasStartedEvent $event
     * @return void
     */
    public function handle(Deployment\DeploymentWasStartedEvent $event): void
    {
        $deployerFileName = sprintf('%s_%s.php', $event->projectId(), $event->number());
        $task = $event->task();

        try {
            // Create the deployer process
            $process = new Process([
                base_path('vendor/bin/dep'),
                '-f=' . $deployerFileName,
                '--ansi',
                '-n',
                '-vvv',
                $task,
            ]);
            $process->setTimeout(600);

            // Run the deployer process and update the deployment log
            $deployment = $this->deploymentRepository->findById(new Deployment\DeploymentNumber($event->number()));
            $process->run(function (string $type, string $buffer) use ($deployment) {
                DB::trancation(function () use (&$log, $deployment) {
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
                    ->finish();
                $this->deploymentRepository->save($deployment);
            });
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
