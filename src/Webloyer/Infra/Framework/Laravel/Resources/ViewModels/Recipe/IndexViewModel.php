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
     * @return array<string, string>
     */
    public function projectCountOf(): array
    {
        return array_reduce($this->recipes->toArray()['data'], function (array $carry, object $recipe): array {
            $carry[$recipe->id] = number_format(count($recipe->projects));
            return $carry;
        }, []);
    }
}
