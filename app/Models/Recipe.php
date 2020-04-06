<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'body',
    ];

    public function projects()
    {
        return $this->belongsToMany('App\Models\Project');
    }

    public function getProjects()
    {
        return $this->projects()->orderBy('name')->get();
    }
}
