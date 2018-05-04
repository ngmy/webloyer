<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server as EntityServer;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;

class Server extends AbstractBaseEloquent
{
    protected $table = 'servers';

    protected $fillable = [
        'name',
        'description',
        'body',
    ];

    /**
     * Convert an Eloquent object into an entity.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server
     */
    public function toEntity()
    {
        return new EntityServer(
            new ServerId($this->id),
            $this->name,
            $this->description,
            $this->body,
            $this->created_at,
            $this->updated_at
        );
    }
}
