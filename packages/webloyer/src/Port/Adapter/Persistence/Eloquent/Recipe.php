<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Project;

class Recipe extends AbstractBaseEloquent
{
    protected $table = 'recipes';

    protected $fillable = [
        'name',
        'description',
        'body',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
