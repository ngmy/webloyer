<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Project;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\User;

class Deployment extends AbstractBaseEloquent
{
    protected $table = 'deployments';

    protected $fillable = [
        'project_id',
        'number',
        'task',
        'status',
        'message',
        'user_id',
    ];

    protected $casts = [
        'number' => 'integer',
        'status' => 'integer',
    ];
}
