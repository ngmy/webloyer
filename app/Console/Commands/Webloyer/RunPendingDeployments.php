<?php
declare(strict_types=1);

namespace App\Console\Commands\Webloyer;

use App\Repositories\Project\ProjectInterface;
use Illuminate\Console\Command;
use App\Services\Deployment\DeployCommanderInterface;

/**
 * Class RunPendingDeployments
 * @package App\Console\Commands\Webloyer
 */
class RunPendingDeployments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webloyer:run-pending-deployments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run pending deployments';

    /**
     * @var ProjectInterface
     */
    protected ProjectInterface $projectRepository;

    /**
     * @var DeployCommanderInterface
     */
    protected DeployCommanderInterface $deployCommander;

    /**
     * RunPendingDeployments constructor.
     * @param ProjectInterface $projectRepository
     * @param DeployCommanderInterface $deployCommander
     */
    public function __construct(ProjectInterface $projectRepository, DeployCommanderInterface $deployCommander)
    {
        parent::__construct();

        $this->deployCommander = $deployCommander;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projects = $this->projectRepository->all();
        foreach ($projects as $project) {
            if ($deployment = $project->lastFreeProjectDeployment()) {
                $this->deployCommander->{'deploy'}($deployment);
            }
        }
    }
}
