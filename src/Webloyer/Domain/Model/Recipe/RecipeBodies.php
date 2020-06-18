<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

class RecipeBodies
{
    /** @var list<RecipeBody> */
    private $recipeBodies;

    /**
     * @param string ...$recipeBodies
     * @return self
     */
    public static function of(string ...$recipeBodies)
    {
        $recipeBodyArray = array_map(function (string $recipeBody) {
            return new RecipeBody($recipeBody);
        }, $recipeBodies);
        return new self(...$recipeBodyArray);
    }

    /**
     * @param RecipeBody ...$recipeBodies
     * @return void
     */
    public function __construct(RecipeBody ...$recipeBodies)
    {
        $this->recipeBodies = $recipeBodies;
    }

    /**
     * @return list<string>
     */
    public function toArray(): array
    {
        return array_map(function (RecipeBody $recipeBody) {
            return $recipeBody->value();
        }, $this->recipeBodies);
    }
}
