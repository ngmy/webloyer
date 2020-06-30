<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Common\App\Service\ApplicationService;
use Webloyer\App\DataTransformer\Deployment\{
    DeploymentDataTransformer,
    DeploymentsDataTransformer,
};
use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentDoesNotExistException,
    DeploymentNumber,
    DeploymentRepository,
};
use Webloyer\Domain\Model\Project\{
    Project,
    ProjectDoesNotExistException,
    ProjectId,
    ProjectRepository,
};
use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeDoesNotExistException,
    RecipeId,
    RecipeRepository,
    Recipes,
};
use Webloyer\Domain\Model\Server\{
    Server,
    ServerId,
    ServerDoesNotExistException,
    ServerRepository,
};
use Webloyer\Domain\Model\User\{
    User,
    UserId,
    UserDoesNotExistException,
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
    /** @var DeploymentDataTransformer */
    protected $deploymentDataTransformer;
    /** @var DeploymentsDataTransformer */
    protected $deploymentsDataTransformer;

    /**
     * @param DeploymentRepository       $deploymentRepository
     * @param ProjectRepository          $projectRepository
     * @param RecipeRepository           $recipeRepository
     * @param ServerRepository           $serverRepository
     * @param UserRepository             $userRepository
     * @param DeploymentDataTransformer  $deploymentDataTransformer
     * @param DeploymentsDataTransformer $deploymentsDataTransformer
     * @return void
     */
    public function __construct(
        DeploymentRepository $deploymentRepository,
        ProjectRepository $projectRepository,
        RecipeRepository $recipeRepository,
        ServerRepository $serverRepository,
        UserRepository $userRepository,
        DeploymentDataTransformer $deploymentDataTransformer,
        DeploymentsDataTransformer $deploymentsDataTransformer
    ) {
        $this->deploymentRepository = $deploymentRepository;
        $this->projectRepository = $projectRepository;
        $this->recipeRepository = $recipeRepository;
        $this->serverRepository = $serverRepository;
        $this->userRepository = $userRepository;
        $this->deploymentDataTransformer = $deploymentDataTransformer;
        $this->deploymentsDataTransformer = $deploymentsDataTransformer;
    }

    /**
     * @return DeploymentDataTransformer
     */
    public function deploymentDataTransformer(): DeploymentDataTransformer
    {
        return $this->deploymentDataTransformer;
    }

    /**
     * @return DeploymentsDataTransformer
     */
    public function deploymentsDataTransformer(): DeploymentsDataTransformer
    {
        return $this->deploymentsDataTransformer;
    }

    /**
     * @param ProjectId $projectId
     * @return Project
     * @throws ProjectDoesNotExistException
     */
    protected function getNonNullProject(ProjectId $projectId): Project
    {
        $project = $this->projectRepository->findById($projectId);
        if (is_null($project)) {
            throw new ProjectDoesNotExistException(
                'Project does not exist.' . PHP_EOL .
                'Id: ' . $projectId->value()
            );
        }
        return $project;
    }

    /**
     * @param Project          $project
     * @param DeploymentNumber $number
     * @return Deployment
     * @throws DeploymentDoesNotExistException
     */
    protected function getNonNullDeployment(
        Project $project,
        DeploymentNumber $number
    ): Deployment {
        $deployment = $this->deploymentRepository->findById($project, $number);
        if (is_null($deployment)) {
            throw new DeploymentDoesNotExistException(
                'Deployment does not exist.' . PHP_EOL .
                'Project Id: ' . $project->id() . PHP_EOL .
                'Number: ' . $number->value()
            );
        }
        return $deployment;
    }

    /**
     * @param RecipeId $recipeId
     * @return Recipe
     * @throws RecipeDoesNotExistException
     */
    protected function getNonNullRecipe(RecipeId $recipeId): Recipe
    {
        $recipe = $this->recipeRepository->findById($recipeId);
        if (is_null($recipe)) {
            throw new RecipeDoesNotExistException(
                'Recipe does not exist.' . PHP_EOL .
                'Id: ' . $recipeId->value()
            );
        }
        return $recipe;
    }

    /**
     * @param ServerId $serverId
     * @return Server
     * @throws ServerDoesNotExistException
     */
    protected function getNonNullServer(ServerId $serverId): Server
    {
        $server = $this->serverRepository->findById($serverId);
        if (is_null($server)) {
            throw new ServerDoesNotExistException(
                'Server does not exist.' . PHP_EOL .
                'Id: ' . $serverId->value()
            );
        }
        return $server;
    }

    /**
     * @param UserId $userId
     * @return User
     * @throws UserDoesNotExistException
     */
    protected function getNonNullUser(UserId $userId): User
    {
        $user = $this->userRepository->findById($userId);
        if (is_null($user)) {
            throw new UserDoesNotExistException(
                'User does not exist.' . PHP_EOL .
                'Id: ' . $userId->value()
            );
        }
        return $user;
    }

    /**
     * @param Deployment $deployment
     * @return void
     * @throws ProjectDoesNotExistException
     * @throws RecipeDoesNotExistException
     * @throws ServerDoesNotExistException
     * @throws UserDoesNotExistException
     */
    protected function requestDeployment(Deployment $deployment): void
    {
        $project = $this->getNonNullProject(new ProjectId($deployment->projectId()));
        $recipes = new Recipes(...array_reduce($project->recipeIds(), function (array $carry, string $recipeId): array {
            $recipe = $this->getNonNullRecipe(new RecipeId($recipeId));
            $carry[] = $recipe;
            return $carry;
        }, []));
        $server = $this->getNonNullServer(new ServerId($project->serverId()));
        $executor = $this->getNonNullUser(new UserId($deployment->executor()));

        $deployment->request(
            $project,
            $recipes,
            $server,
            $executor
        );
    }
}
