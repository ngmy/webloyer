<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var list<object> */
    private $recipes;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param list<object> $recipes
     * @return void
     */
    public function __construct(array $recipes)
    {
        $this->recipes = $recipes;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function recipes(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            array_slice(
                $this->recipes,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($this->recipes),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
    }

    /**
     * @return array<string, string>
     */
    public function recipeProjectCountOf(): array
    {
        return array_reduce($this->recipes, function (array $carry, object $recipe): array {
            $carry[$recipe->id] = number_format(count($recipe->projects));
            return $carry;
        }, []);
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
}
