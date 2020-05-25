<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Deployer\Domain\Model\DeployerFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Webloyer\Domain\Model\Deployment;

class LaravelDeployerFinishedListener implements ShouldQueue
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
     * @param DeployerFinished $event
     * @return void
     */
    public function handle(DeployerFinished $event): void
    {
        DB::transaction(function () use ($event) {
            $deployment = $event->deployment();
            $deployment->changeLog($event->log())
                ->changeStatus($event->status())
                ->changeFinishDate($event->finishDate())
                ->complete(
                    $event->project(),
                    $event->recipes(),
                    $event->server(),
                    $event->executor()
                );
            $this->deploymentRepository->save($deployment);
        });
    }
}
