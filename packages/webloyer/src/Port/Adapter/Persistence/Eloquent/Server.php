<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;

class Server extends AbstractBaseEloquent
{
    protected $table = 'servers';

    protected $fillable = [
        'name',
        'description',
        'body',
    ];
}
