<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Common\App\Service\ApplicationService;
use Deployer\Domain\Model\DeployerProgressed;
use stdClass;
use Webloyer\App\Service\Deployment\ProgressDeploymentRequest;

class DeployerProgressedListener extends WebloyerEventListener
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
        return $typeName == DeployerProgressed::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function perform(stdClass $event): void
    {
        $request = (new ProgressDeploymentRequest())
            ->setProjectId($event->project_id)
            ->setNumber($event->number)
            ->setLog($event->log);
        $this->service->execute($request);
    }
}
