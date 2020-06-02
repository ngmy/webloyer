<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe;

use Spatie\ViewModels\ViewModel;

class ShowViewModel extends ViewModel
{
    private $recipe;

    public function __construct(object $recipe)
    {
        $this->recipe = $recipe;
    }

    public function recipe(): object
    {
        return $this->recipe;
    }

    public function isRecipeHasProjects(object $recipe): bool
    {
        return !empty($recipe->projects);
    }

    public function recipeProjectCount(object $recipe): int
    {
        return count($recipe->projects);
    }
}
