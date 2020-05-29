<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Recipe;

use Webloyer\App\DataTransformer\Project\ProjectsDataTransformer;
use Webloyer\Domain\Model\Recipe\Recipe;

/**
 * @codeCoverageIgnore
 */
interface RecipeDataTransformer
{
    /**
     * @param Recipe $recipe
     * @return self
     */
    public function write(Recipe $recipe): self;
    /**
     * @return mixed
     */
    public function read();
    /**
     * @param ProjectsDataTransformer $projectsDataTransformer
     * @return self
     */
    public function setProjectsDataTransformer(ProjectsDataTransformer $projectsDataTransformer): self;
}
