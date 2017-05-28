<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;

class MaxDeployment extends AbstractBaseEloquent
{
    protected $table = 'max_deployments';

    protected $fillable = ['project_id', 'number'];

    protected $casts = [
        'number' => 'integer',
    ];
}
