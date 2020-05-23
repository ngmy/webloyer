<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use DB;
use Deployer\Domain\Model\DeployerStarted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        DB::trancation(function () use ($event) {
            $deployment = $event->deployment();
            $deployment->changeStatus();
            $deployment->changeStartDate();
            $this->deploymentRepository->save($deployment);
        });
    }
}
