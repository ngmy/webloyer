<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var LengthAwarePaginator<object> */
    private $recipes;

    /**
     * @param LengthAwarePaginator<object> $recipes
     * @return void
     */
    public function __construct(LengthAwarePaginator $recipes)
    {
        $this->recipes = $recipes;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function recipes(): LengthAwarePaginator
    {
        return $this->recipes;
    }

    /**
     * @param object $recipe
     * @return string
     */
    public function projectCountFrom(object $recipe): string
    {
        return number_format(count($recipe->projects));
    }
}
