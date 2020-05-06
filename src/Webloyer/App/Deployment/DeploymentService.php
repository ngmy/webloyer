<?php

declare(strict_types=1);

namespace Webloyer\App\Deployment;

use DB;
use InvalidArgumentException;
use Webloyer\App\Deployment\Commands;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Project\ProjectRepository;
use Webloyer\Domain\Model\Recipe\Recipe;
use Webloyer\Domain\Model\Recipe\RecipeId;
use Webloyer\Domain\Model\Recipe\RecipeRepository;
use Webloyer\Domain\Model\Server\ServerId;
use Webloyer\Domain\Model\Server\ServerRepository;
use Webloyer\Domain\Model\User\UserId;
use Webloyer\Domain\Model\User\UserRepository;

class DeploymentService
{
    /** @var Deployment\DeploymentRepository */
    private $deploymentRepository;
    private $projectRepository;
    private $recipeRepository;
    private $serverRepository;
    private $userRepository;

    /**
     * @param Deployment\DeploymentRepository $deploymentRepository
     * @return void
     */
    public function __construct(
        Deployment\DeploymentRepository $deploymentRepository,
        ProjectRepository $projectRepository,
        RecipeRepository $recipeRepository,
        ServerRepository $serverRepository,
        UserRepository $userRepository
    ) {
        $this->deploymentRepository = $deploymentRepository;
        $this->projectRepository = $projectRepository;
        $this->recipeRepository = $recipeRepository;
        $this->serverRepository = $serverRepository;
        $this->userRepository = $userRepository;
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
                'now',
                null,
                null
            );
            $this->requestDeployment($deployment);
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
                'now',
                null,
                null
            );
            $this->requestDeployment($deployment);
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

    private function requestDeployment(Deployment\Deployment $deployment): void
    {
        $project = $this->projectRepository->findById(new ProjectId($deployment->projectId()));
        $recipes = array_map(function (string $recipeId): Recipe {
            return $this->recipeRepository->findById(new RecipeId($recipeId));
        }, $project->recipeIds());
        $server = $this->serverRepository->findById(new ServerId($project->serverId()));
        $executor = $this->userRepository->findByEmail(new UserId($deployment->executor()));

        $deployment->request(
            $project,
            $recipes,
            $server,
            $executor
        );
    }
}
