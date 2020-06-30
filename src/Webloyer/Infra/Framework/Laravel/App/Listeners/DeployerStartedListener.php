<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Common\App\Service\ApplicationService;
use Deployer\Domain\Model\DeployerStarted;
use stdClass;
use Webloyer\App\Service\Deployment\StartDeploymentRequest;

class DeployerStartedListener extends WebloyerEventListener
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
        return $typeName == DeployerStarted::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function perform(stdClass $event): void
    {
        $request = (new StartDeploymentRequest())
            ->setProjectId($event->project_id)
            ->setNumber($event->number)
            ->setStartDate($event->start_date);
        $this->service->execute($request);
    }
}
