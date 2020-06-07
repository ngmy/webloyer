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
    private $deploymentRepository;
    private $recipeRepository;
    private $serverRepository;
    private $userRepository;

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

    public function lastDeploymentFrom(ProjectId $projectId): Deployment
    {
        return $this->deploymentRepository->findLastByProjectId($projectId);
    }

    public function serverFrom(ServerId $serverId): ?Server
    {
        return $this->serverRepository->findById($serverId);
    }

    public function userFrom(UserId $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}
