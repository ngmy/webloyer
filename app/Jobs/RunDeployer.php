<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ngmy\Webloyer\Webloyer\Application\Deployer\DeployerService;

class RunDeployer extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $projectId;

    private $deploymentId;

    /**
     * Create a new job instance.
     *
     * @param int $projectId
     * @param int $deploymentId
     * @return void
     */
    public function __construct($projectId, $deploymentId)
    {
        $this->projectId = $projectId;
        $this->deploymentId = $deploymentId;
    }

    /**
     * Execute the job.
     *
     * @param \Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService $deployerService
     * @return void
     */
    public function handle(DeployerService $deployerService)
    {
        $deployerService->runDeployer(
            $this->projectId,
            $this->deploymentId
        );
    }
}
