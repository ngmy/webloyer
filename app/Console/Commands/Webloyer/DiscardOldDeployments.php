<?php

namespace App\Console\Commands\Webloyer;

use DateTimeImmutable;
use Illuminate\Console\Command;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;

class DiscardOldDeployments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webloyer:discard-old-deployments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discard old deployments';

    protected $projectService;

    protected $deploymentService;

    protected $currentDate;

    /**
     * Create a new command instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Application\Project\ProjectService       $projectService
     * @param \Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService $deploymentService
     * @return void
     */
    public function __construct(ProjectService $projectService, DeploymentService $deploymentService)
    {
        parent::__construct();

        $this->projectService = $projectService;
        $this->deploymentService = $deploymentService;
        $this->currentDate = new DateTimeImmutable();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projects = $this->projectService->getAllProjects();
        foreach ($projects as $project) {
            $oldDeployments = $this->deploymentService->removeOldDeploymentsOfProject($project->projectId()->id(), $this->currentDate);
        }
    }
}
