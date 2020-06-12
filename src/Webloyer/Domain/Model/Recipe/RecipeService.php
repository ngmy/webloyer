<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

use Webloyer\Domain\Model\Project\ProjectRepository;
use Webloyer\Domain\Model\Project\Projects;

class RecipeService
{
    /** @var ProjectRepository */
    private $projectRepository;

    /**
     * @param ProjectRepository $projectRepository
     * @return void
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param RecipeId $recipeId
     * @return Projects
     */
    public function projectsFrom(RecipeId $recipeId): Projects
    {
        return $this->projectRepository->findAllByRecipeId($recipeId);
    }
}
