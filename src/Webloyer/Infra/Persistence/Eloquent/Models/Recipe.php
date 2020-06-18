<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webloyer\Domain\Model\Recipe\{
    Recipe as RecipeEntity,
    RecipeInterest,
};
use Webloyer\Infra\Persistence\Eloquent\ImmutableTimestampable;

/**
 * Webloyer\Infra\Persistence\Eloquent\Models\Recipe
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $body
 * @property \CarbonImmutable $created_at
 * @property \CarbonImmutable $updated_at
 * @property string $uuid
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webloyer\Infra\Persistence\Eloquent\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe ofId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe whereUuid($value)
 * @mixin \Eloquent
 */
class Recipe extends Model implements RecipeInterest
{
    use ImmutableTimestampable;

    /** @var list<string> */
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'body',
    ];

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
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * @param string $id
     * @return void
     * @see RecipeInterest::informId()
     */
    public function informId(string $id): void
    {
        $this->uuid = $id;
    }

    /**
     * @param string $name
     * @return void
     * @see RecipeInterest::informName()
     */
    public function informName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string|null $description
     * @return void
     * @see RecipeInterest::informDescription()
     */
    public function informDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $body
     * @return void
     * @see RecipeInterest::informBody()
     */
    public function informBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return RecipeEntity
     */
    public function toEntity(): RecipeEntity
    {
        return RecipeEntity::of(
            $this->uuid,
            $this->name,
            $this->description,
            $this->body
        )
        ->setSurrogateId($this->id)
        ->setCreatedAt($this->created_at)
        ->setUpdatedAt($this->updated_at);
    }
}
