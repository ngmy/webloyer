<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\{
    Builder,
    Model,
};
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webloyer\Domain\Model\Server\{
    Server as ServerEntity,
    ServerInterest,
};
use Webloyer\Infra\Persistence\Eloquent\ImmutableTimestampable;

/**
 * Webloyer\Infra\Persistence\Eloquent\Models\Server
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $body
 * @property \CarbonImmutable $created_at
 * @property \CarbonImmutable $updated_at
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server ofId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Server whereUuid($value)
 * @mixin \Eloquent
 */
class Server extends Model implements ServerInterest
{
    use ImmutableTimestampable;

    /** @var array<int, string> */
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
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @param string $id
     * @return void
     * @see ServerInterest::informId()
     */
    public function informId(string $id): void
    {
        $this->uuid = $id;
    }

    /**
     * @param string $name
     * @return void
     * @see ServerInterest::informName()
     * @return void
     */
    public function informName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string|null $description
     * @return void
     * @see ServerInterest::informDescription()
     */
    public function informDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $body
     * @return void
     * @see ServerInterest::informBody()
     */
    public function informBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return ServerEntity
     */
    public function toEntity(): ServerEntity
    {
        return ServerEntity::of(
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
