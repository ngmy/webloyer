<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Recipe;

use Webloyer\Domain\Model\Recipe\Recipes;

/**
 * @codeCoverageIgnore
 */
interface RecipesDataTransformer
{
    /**
     * @param Recipes $recipes
     * @return self
     */
    public function write(Recipes $recipes): self;
    /**
     * @return mixed
     */
    public function read();
}
