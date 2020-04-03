<?php

namespace App\Console\Commands\Webloyer;

use App\Repositories\Project\ProjectInterface;
use App\Specifications\OldDeploymentSpecification;
use Illuminate\Console\Command;
use DB;
use DateTime;

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

    protected $projectRepository;

    protected $spec;

    /**
     * Create a new command instance.
     *
     * @param \App\Repositories\Project\ProjectInterface $projectRepository
     * @return void
     */
    public function __construct(ProjectInterface $projectRepository)
    {
        parent::__construct();

        $this->projectRepository = $projectRepository;
        $this->spec = new OldDeploymentSpecification(new DateTime);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {
            $projects = $this->projectRepository->all();
            foreach ($projects as $project) {
                $oldDeployments = $project->getSatisfyingDeployments($this->spec);
                if (!$oldDeployments->isEmpty()) {
                    $project->deleteDeployments($oldDeployments);
                }
            }
        });
    }
}
