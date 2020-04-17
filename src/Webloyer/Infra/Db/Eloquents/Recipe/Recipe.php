<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Eloquents\Recipe;

use Illuminate\Database\Eloquent\{
    Builder,
    Model,
};
use Webloyer\Domain\Model\Recipe as RecipeDomainModel;

class Recipe extends Model implements RecipeDomainModel\RecipeInterest
{
    protected $fillable = ['uuid'];

    /**
     * @param Builder $query
     * @param string  $id
     * @return Builder
     */
    public function scopeOfId(Builder $query, string $id): Builder
    {
        return $query->where('uuid', $id);
    }

    /**
     * @param string $id
     * @return void
     */
    public function informId(string $id): void
    {
        $this->uuid = $id;
    }

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

    /**
     * @return RecipeDomainModel\Recipe
     */
    public function toEntity(): RecipeDomainModel\Recipe
    {
        return RecipeDomainModel\Recipe::of(
            $this->uuid,
            $this->name,
            $this->description,
            $this->body
        )->setSurrogateId($this->id);
    }
}
