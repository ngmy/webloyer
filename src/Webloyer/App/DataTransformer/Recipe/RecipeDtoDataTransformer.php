<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Recipe;

use Webloyer\App\DataTransformer\Project\ProjectsDataTransformer;
use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeId,
    RecipeInterest,
    RecipeService,
};

class RecipeDtoDataTransformer implements RecipeDataTransformer
{
    /** @var Recipe */
    private $recipe;
    /** @var RecipeService */
    private $recipeService;
    /** @var ProjectsDataTransformer */
    private $projectsDataTransformer;

    /**
     * @param RecipeService $recipeService
     * @return void
     */
    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
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
            /** @var string */
            public $id;
            /** @var string */
            public $name;
            /** @var string|null */
            public $description;
            /** @var string */
            public $body;
            /** @var list<object>|null */
            public $projects;
            /** @var int */
            public $surrogateId;
            /** @var string */
            public $createdAt;
            /** @var string */
            public $updatedAt;
            /**
             * @param string $id
             * @return void
             */
            public function informId(string $id): void
            {
                $this->id = $id;
            }
            /**
             * @param string $name
             * @return void
             */
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            /**
             * @param string|null $description
             * @return void
             */
            public function informDescription(?string $description): void
            {
                $this->description = $description;
            }
            /**
             * @param string $body
             * @return void
             */
            public function informBody(string $body): void
            {
                $this->body = $body;
            }
        };
        $this->recipe->provide($dto);

        if (isset($this->projectsDataTransformer)) {
            $projects = $this->recipeService->projectsFrom(new RecipeId($this->recipe->id()));
            $dto->projects = $this->projectsDataTransformer->write($projects)->read();
        }

        $dto->surrogateId = $this->recipe->surrogateId();
        assert(!is_null($this->recipe->createdAt()));
        $dto->createdAt = $this->recipe->createdAt();
        assert(!is_null($this->recipe->updatedAt()));
        $dto->updatedAt = $this->recipe->updatedAt();

        return $dto;
    }

    /**
     * @param ProjectsDataTransformer $projectsDataTransformer
     * @return self
     */
    public function setProjectsDataTransformer(ProjectsDataTransformer $projectsDataTransformer): self
    {
        $this->projectsDataTransformer = $projectsDataTransformer;
        return $this;
    }
}
