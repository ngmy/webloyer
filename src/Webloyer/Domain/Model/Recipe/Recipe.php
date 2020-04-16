<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

class Recipe
{
    /** @var RecipeName */
    private $name;
    /** @var RecipeDescription */
    private $description;
    /** @var RecipeBody */
    private $body;

    public static function of(
        string $name,
        ?string $description,
        string $body
    ): self {
        return new self(
            new RecipeName($name),
            new RecipeDescription($description),
            new RecipeBody($body)
        );
    }

    /**
     * @param RecipeName        $name
     * @param RecipeDescription $description
     * @param RecipeBody        $body
     * @return void
     */
    public function __construct(
        RecipeName $name,
        RecipeDescription $description,
        RecipeBody $body
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->body = $body;
    }

    public function provide(RecipeInterest $interest): void
    {
        $interest->informName($this->name->value());
        $interest->informDescription($this->description->value());
        $interest->informBody($this->body->value());
    }
}
