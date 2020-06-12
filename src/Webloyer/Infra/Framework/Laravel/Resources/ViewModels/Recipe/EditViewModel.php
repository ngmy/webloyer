<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe;

use Spatie\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
    /** @var object $recipe */
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
}
