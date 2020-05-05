<?php

declare(strict_types=1);

namespace Webloyer\App\Deployment;

use DB;
use InvalidArgumentException;
use Webloyer\App\Deployment\Commands;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Domain\Model\Project\ProjectId;

class DeploymentService
{
    /** @var Deployment\DeploymentRepository */
    private $deploymentRepository;

    /**
     * @param Deployment\DeploymentRepository $deploymentRepository
     * @return void
     */
    public function __construct(Deployment\DeploymentRepository $deploymentRepository)
    {
        $this->deploymentRepository = $deploymentRepository;
    }

    /**
     * @param Commands\GetDeploymentsCommand $command
     * @return Deployment\Deployments
     */
    public function getDeployments(Commands\GetDeploymentsCommand $command): Deployment\Deployments
    {
        return $this->deploymentRepository->findAllByPage($command->getPage(), $command->getPerPage());
    }

    /**
     * @param Commands\GetDeploymentCommand $command
     * @return Deployment\Deployment
     */
    public function getDeployment(Commands\GetDeploymentCommand $command): Deployment\Deployment
    {
        $projectId = new ProjectId($command->getProjectId());
        $number = new Deployment\DeploymentNumber($command->getNumber());
        return $this->getNonNullDeployment($projectId, $number);
    }

    /**
     * @param Commands\DeployCommand $command
     * @return void
     */
    public function deploy(Commands\DeployCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $deployment = Deployment\Deployment::of(
                $command->getProjectId(),
                $command->getNumber(),
                Deployment\DeploymentTask::rollback()->value(),
                Deployment\DeploymentStatus::queued()->value(),
                '',
                $command->getExecutor(),
                null
            );
            $this->deploymentRepository->save($deployment);
        });
    }

    /**
     * @param Commands\RollbackCommand $command
     * @return void
     */
    public function rollback(Commands\RollbackCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $deployment = Deployment\Deployment::of(
                $command->getProjectId(),
                $command->getNumber(),
                Deployment\DeploymentTask::rollback()->value(),
                Deployment\DeploymentStatus::queued()->value(),
                '',
                $command->getExecutor(),
                null
            );
            $this->deploymentRepository->save($deployment);
        });
    }

    /**
     * @param Commands\DeleteDeploymentCommand $command
     * @return void
     */
    public function deleteDeployment(Commands\DeleteDeploymentCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $projectId = new ProjectId($command->getProjectId());
            $number = new Deployment\DeploymentNumber($command->getNumber());
            $deployment = $this->getNonNullDeployment($projectId, $number);
            $this->deploymentRepository->remove($deployment);
        });
    }

    /**
     * @param ProjectId                   $projectId
     * @param Deployment\DeploymentNumber $number
     * @return Deployment\Deployment
     * @throws InvalidArgumentException
     */
    private function getNonNullDeployment(
        ProjectId $projectId,
        Deployment\DeploymentNumber $number
    ): Deployment\Deployment {
        $deployment = $this->deploymentRepository->findById($projectId, $number);
        if (is_null($deployment)) {
            throw new InvalidArgumentException(
                'Deployment does not exists.' . PHP_EOL .
                'Project Id: ' . $projectId->value() . PHP_EOL .
                'Number: ' . $number->value()
            );
        }
        return $deployment;
    }
}
