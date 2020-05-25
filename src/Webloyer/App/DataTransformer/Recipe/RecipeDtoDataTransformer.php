<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Recipe;

use Webloyer\App\DataTransformer\Project\ProjectsDtoDataTransformer;
use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeId,
    RecipeInterest,
    RecipeService,
};

class RecipeDtoDataTransformer implements RecipeDataTransformer
{
    private $recipe;
    private $recipeService;
    private $projectsDataTransformer;

    public function __construct(RecipeService $recipeService, ProjectsDtoDataTransformer $projectsDataTransformer)
    {
        $this->recipeService = $recipeService;
        $this->projectsDataTransformer = $projectsDataTransformer;
    }

    /**
     * @param Recipe $recipe
     * @return self
     */
    public function write(Recipe $recipe): self
    {
        $this->recipe = $recipe;
        return $this;
    }

    /**
     * @return object
     */
    public function read()
    {
        $dto = new class implements RecipeInterest {
            public function informId(string $id): void
            {
                $this->id = $id;
            }
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            public function informDescription(?string $description): void
            {
                $this->description = $description;
            }
            public function informBody(string $body): void
            {
                $this->body = $body;
            }
        };
        $this->recipe->provide($dto);

        $projects = $this->recipeService->projectsFrom(new RecipeId($this->recipe->id()));
        $dto->projects = $this->projectsDataTransformer->write($projects)->read();
        $dto->projectCount = $projects->count();

        $dto->surrogateId = $this->recipe->surrogateId();
        $dto->createdAt = $this->recipe->createdAt();
        $dto->updatedAt = $this->recipe->updatedAt();

        return $dto;
    }
}
