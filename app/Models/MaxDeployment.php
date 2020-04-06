<?php

namespace App\Models;

class MaxDeployment extends BaseModel
{
    protected $fillable = ['project_id', 'number'];

    protected $casts = [
        'number' => 'integer',
    ];
}
