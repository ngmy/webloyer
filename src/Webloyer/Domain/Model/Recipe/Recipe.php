<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

use Common\Domain\Model\Identity\Identifiable;

class Recipe
{
    use Identifiable;

    /** @var RecipeId */
    private $id;
    /** @var RecipeName */
    private $name;
    /** @var RecipeDescription */
    private $description;
    /** @var RecipeBody */
    private $body;

    /**
     * @param string      $id
     * @param string      $name
     * @param string|null $description
     * @param string      $body
     * @return self
     */
    public static function of(
        string $id,
        string $name,
        ?string $description,
        string $body
    ): self {
        return new self(
            new RecipeId($id),
            new RecipeName($name),
            new RecipeDescription($description),
            new RecipeBody($body)
        );
    }

    /**
     * @param RecipeId          $id
     * @param RecipeName        $name
     * @param RecipeDescription $description
     * @param RecipeBody        $body
     * @return void
     */
    public function __construct(
        RecipeId $id,
        RecipeName $name,
        RecipeDescription $description,
        RecipeBody $body
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id->value();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name->value();
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description->value();
    }

    /**
     * @return string
     */
    public function body(): string
    {
        return $this->body->value();
    }

    /**
     * @param RecipeName $name
     * @return self
     */
    public function changeName(RecipeName $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param RecipeDescription $description
     * @return self
     */
    public function changeDescription(RecipeDescription $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param RecipeBody $body
     * @return self
     */
    public function changeBody(RecipeBody $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param RecipeInterest $interest
     * @return void
     */
    public function provide(RecipeInterest $interest): void
    {
        $interest->informId($this->id());
        $interest->informName($this->name());
        $interest->informDescription($this->description());
        $interest->informBody($this->body());
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        $equalObjects = false;

        if ($object instanceof self) {
            $equalObjects = $object->id == $this->id;
        }

        return $equalObjects;
    }
}
