<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe;

use Spatie\ViewModels\ViewModel;

class ShowViewModel extends ViewModel
{
    /** @var object */
    private $recipe;

    /**
     * @param object $recipe
     * @return void
     */
    public function __construct(object $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * @return object
     */
    public function recipe(): object
    {
        return $this->recipe;
    }

    /**
     * @param object $recipe
     * @return bool
     */
    public function isRecipeHasProjects(object $recipe): bool
    {
        return !empty($recipe->projects);
    }

    /**
     * @param object $recipe
     * @return int
     */
    public function recipeProjectCount(object $recipe): int
    {
        return count($recipe->projects);
    }
}
