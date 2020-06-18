<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

class RecipeIds
{
    /** @var list<RecipeId> */
    private $recipeIds;

    /**
     * @param string ...$recipeIds
     * @return self
     */
    public static function of(string ...$recipeIds)
    {
        $recipeIdArray = array_map(function (string $recipeId) {
            return new RecipeId($recipeId);
        }, $recipeIds);
        return new self(...$recipeIdArray);
    }

    /**
     * @param RecipeId ...$recipeIds
     * @return void
     */
    public function __construct(RecipeId ...$recipeIds)
    {
        $this->recipeIds = $recipeIds;
    }

    /**
     * @return list<string>
     */
    public function toArray(): array
    {
        return array_map(function (RecipeId $recipeId) {
            return $recipeId->value();
        }, $this->recipeIds);
    }
}
