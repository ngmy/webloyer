<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Common\App\Service\ApplicationService;
use Deployer\Domain\Model\DeployerFinished;
use stdClass;
use Webloyer\App\Service\Deployment\FinishDeploymentRequest;

class DeployerFinishedListener extends WebloyerEventListener
{
    /** @var ApplicationService */
    private $service;

    /**
     * Create the event listener.
     *
     * @param ApplicationService $service
     * @return void
     */
    public function __construct(ApplicationService $service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    protected function listensTo(string $typeName): bool
    {
        return $typeName == DeployerFinished::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function perform(stdClass $event): void
    {
        $request = (new FinishDeploymentRequest())
            ->setProjectId($event->project_id)
            ->setNumber($event->number)
            ->setLog($event->log)
            ->setStatus($event->status)
            ->setFinishDate($event->finish_date);
        $this->service->execute($request);
    }
}
