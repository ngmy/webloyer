<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\{
    Builder,
    Model,
};
use Webloyer\Domain\Model\Server as ServerDomainModel;

class Server extends Model implements ServerDomainModel\ServerInterest
{
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
     * @param string $id
     * @return void
     * @see ServerDomainModel\ServerInterest::informId()
     */
    public function informId(string $id): void
    {
        $this->uuid = $id;
    }

    /**
     * @param string $name
     * @return void
     * @see ServerDomainModel\ServerInterest::informName()
     * @return void
     */
    public function informName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string|null $description
     * @return void
     * @see ServerDomainModel\ServerInterest::informDescription()
     */
    public function informDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $body
     * @return void
     * @see ServerDomainModel\ServerInterest::informBody()
     */
    public function informBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return ServerDomainModel\Server
     */
    public function toEntity(): ServerDomainModel\Server
    {
        return ServerDomainModel\Server::of(
            $this->uuid,
            $this->name,
            $this->description,
            $this->body
        )->setSurrogateId($this->id);
    }
}
