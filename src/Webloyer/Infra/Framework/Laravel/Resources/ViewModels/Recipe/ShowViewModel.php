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
     * @return bool
     */
    public function isRecipeHasProjects(): bool
    {
        return !empty($this->recipe->projects);
    }

    /**
     * @return int
     */
    public function recipeProjectCount(): int
    {
        return count($this->recipe->projects);
    }
}
