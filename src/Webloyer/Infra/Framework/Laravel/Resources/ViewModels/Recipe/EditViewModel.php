<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe;

use Spatie\ViewModels\ViewModel;

class EditViewModel extends ViewModel
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
}
