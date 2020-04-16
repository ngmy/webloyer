<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Eloquents\Recipe;

use Illuminate\Database\Eloquent\Model;
use Webloyer\Domain\Model\Recipe\RecipeInterest;

class Recipe extends Model implements RecipeInterest
{
    /**
     * @param string $name
     * @return void
     */
    public function informName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function informDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $body
     * @return void
     */
    public function informBody(string $body): void
    {
        $this->body = $body;
    }
}
