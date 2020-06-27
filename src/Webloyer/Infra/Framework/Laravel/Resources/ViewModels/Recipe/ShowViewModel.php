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
    public function isRecipeHasProject(): bool
    {
        return !empty($this->recipe->projects);
    }

    /**
     * @return string
     */
    public function recipeProjectCount(): string
    {
        return number_format(count($this->recipe->projects));
    }
}
