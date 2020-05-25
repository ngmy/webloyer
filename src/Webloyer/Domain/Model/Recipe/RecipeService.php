<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

use Webloyer\Domain\Model\Project\ProjectRepository;
use Webloyer\Domain\Model\Project\Projects;

class RecipeService
{
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function projectsFrom(RecipeId $recipeId): Projects
    {
        return $this->projectRepository->findAllByRecipeId($recipeId);
    }
}
