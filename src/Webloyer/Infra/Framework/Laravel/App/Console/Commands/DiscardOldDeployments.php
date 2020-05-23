<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Console\Commands;

use Illuminate\Console\Command;
use Webloyer\App\Service\Deployment\DeleteOldDeploymentsRequest;
use Webloyer\App\Service\Deployment\DeleteOldDeploymentsService;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(DeleteOldDeploymentsService $service)
    {
        $request = (new DeleteOldDeploymentsRequest())->setDateTime('now');
        $service->execute($request);
    }
}
