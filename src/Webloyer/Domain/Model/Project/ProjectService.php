<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentRepository,
};
use Webloyer\Domain\Model\Recipe\{
    RecipeRepository,
    RecipeId,
    RecipeIds,
    Recipes,
};
use Webloyer\Domain\Model\Server\{
    ServerRepository,
    ServerId,
    Server,
};
use Webloyer\Domain\Model\User\{
    UserRepository,
    UserId,
    User,
};

class ProjectService
{
    /** @var DeploymentRepository */
    private $deploymentRepository;
    /** @var RecipeRepository */
    private $recipeRepository;
    /** @var ServerRepository */
    private $serverRepository;
    /** @var UserRepository */
    private $userRepository;

    /**
     * @param DeploymentRepository $deploymentRepository
     * @param RecipeRepository     $recipeRepository
     * @param ServerRepository     $serverRepository
     * @param UserRepository       $userRepository
     * @return void
     */
    public function __construct(
        DeploymentRepository $deploymentRepository,
        RecipeRepository $recipeRepository,
        ServerRepository $serverRepository,
        UserRepository $userRepository
    ) {
        $this->deploymentRepository = $deploymentRepository;
        $this->recipeRepository = $recipeRepository;
        $this->serverRepository = $serverRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param RecipeIds $recipeIds
     * @return Recipes
     */
    public function recipesFrom(RecipeIds $recipeIds): Recipes
    {
        return new Recipes(...array_reduce($recipeIds->toArray(), function (array $carry, string $recipeId): array {
            $recipe = $this->recipeRepository->findById(new RecipeId($recipeId));
            if (is_null($recipe)) {
                return $carry;
            }
            $carry[] = $recipe;
            return $carry;
        }, []));
    }

    /**
     * @param ProjectId $projectId
     * @return Deployment|null
     */
    public function lastDeploymentFrom(ProjectId $projectId): ?Deployment
    {
        return $this->deploymentRepository->findLastByProjectId($projectId);
    }

    /**
     * @param ServerId $serverId
     * @return Server|null
     */
    public function serverFrom(ServerId $serverId): ?Server
    {
        return $this->serverRepository->findById($serverId);
    }

    /**
     * @param UserId $userId
     * @return User|null
     */
    public function userFrom(UserId $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}
