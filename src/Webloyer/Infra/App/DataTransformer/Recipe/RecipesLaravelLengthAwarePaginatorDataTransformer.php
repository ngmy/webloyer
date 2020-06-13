<?php

declare(strict_types=1);

namespace Webloyer\Infra\App\DataTransformer\Recipe;

use Illuminate\Pagination\LengthAwarePaginator;
use Webloyer\App\DataTransformer\Recipe\{
    RecipeDataTransformer,
    RecipesDataTransformer,
    RecipesDtoDataTransformer,
};
use Webloyer\Domain\Model\Recipe\Recipes;

class RecipesLaravelLengthAwarePaginatorDataTransformer implements RecipesDataTransformer
{
    /** @var Recipes */
    private $recipes;
    /** @var RecipesDtoDataTransformer */
    private $recipesDataTransformer;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param RecipesDtoDataTransformer $recipesDataTransformer
     * @return void
     */
    public function __construct(RecipesDtoDataTransformer $recipesDataTransformer)
    {
        $this->recipesDataTransformer = $recipesDataTransformer;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @param int $perPage
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @param Recipes $recipes
     * @return self
     */
    public function write(Recipes $recipes): self
    {
        $this->recipes = $recipes;
        return $this;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function read()
    {
        $recipes = $this->recipesDataTransformer->write($this->recipes)->read();
        return new LengthAwarePaginator(
            array_slice(
                $recipes,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($recipes),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
    }

    /**
     * @return RecipeDataTransformer
     */
    public function recipeDataTransformer(): RecipeDataTransformer
    {
        return $this->recipesDataTransformer->recipeDataTransformer();
    }
}
