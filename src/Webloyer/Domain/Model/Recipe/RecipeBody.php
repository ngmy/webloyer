<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

class RecipeBody
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     * @return void
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
