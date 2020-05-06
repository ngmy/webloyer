<?php

declare(strict_types=1);

namespace Webloyer\Infra\Messaging\LaravelQueue;

use DB;
use Deployer\Domain\Model\DeployerProgressed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webloyer\Domain\Model\Deployment;

class LaravelQueueDeployerProgressedListener implements ShouldQueue
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
     * @param DeployerProgressed $event
     * @return void
     */
    public function handle(DeployerProgressed $event): void
    {
        DB::trancation(function () use ($event) {
            $deployment = $event->deployment();
            $deployment->appendLog($event->log());
            $this->deploymentRepository->save($deployment);
        });
    }
}
