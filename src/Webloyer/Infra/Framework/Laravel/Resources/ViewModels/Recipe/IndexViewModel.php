<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    private $recipes;

    public function __construct(LengthAwarePaginator $recipes)
    {
        $this->recipes = $recipes;
    }

    public function recipes(): LengthAwarePaginator
    {
        return $this->recipes;
    }

    public function projectCountFrom(object $recipe): string
    {
        return number_format(count($recipe->projects));
    }
}
