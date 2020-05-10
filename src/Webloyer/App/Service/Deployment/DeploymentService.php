<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Common\App\Service\ApplicationService;
use InvalidArgumentException;
use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentNumber,
    DeploymentRepository,
};
use Webloyer\Domain\Model\Project\{
    ProjectId,
    ProjectRepository,
};
use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeId,
    RecipeRepository,
};
use Webloyer\Domain\Model\Server\{
    ServerId,
    ServerRepository,
};
use Webloyer\Domain\Model\User\{
    UserId,
    UserRepository,
};

abstract class DeploymentService implements ApplicationService
{
    /** @var DeploymentRepository */
    protected $deploymentRepository;
    /** @var ProjectRepository */
    protected $projectRepository;
    /** @var RecipeRepository */
    protected $recipeRepository;
    /** @var ServerRepository */
    protected $serverRepository;
    /** @var UserRepository */
    protected $userRepository;

    /**
     * @param DeploymentRepository $deploymentRepository
     * @param ProjectRepository $projectRepository
     * @param RecipeRepository $recipeRepository
     * @param ServerRepository $serverRepository
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(
        DeploymentRepository $deploymentRepository,
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
     * @param ProjectId $projectId
     * @param DeploymentNumber $number
     * @return Deployment
     * @throws InvalidArgumentException
     */
    protected function getNonNullDeployment(
        ProjectId $projectId,
        DeploymentNumber $number
    ): Deployment {
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

    /**
     * @param Deployment $deployment
     * @return void
     */
    protected function requestDeployment(Deployment $deployment): void
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
