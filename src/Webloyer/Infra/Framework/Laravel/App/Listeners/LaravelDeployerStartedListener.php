<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Deployer\Domain\Model\DeployerStarted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Webloyer\Domain\Model\Deployment;

class LaravelDeployerStartedListener implements ShouldQueue
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
     * @param DeployerStarted $event
     * @return void
     */
    public function handle(DeployerStarted $event): void
    {
        DB::transaction(function () use ($event) {
            $deployment = $this->deploymentRepository->findById($event->projectId(), $event->number());
            $deployment->changeStatus('running');
            $deployment->changeStartDate($event->startDate());
            $this->deploymentRepository->save($deployment);
        });
    }
}
