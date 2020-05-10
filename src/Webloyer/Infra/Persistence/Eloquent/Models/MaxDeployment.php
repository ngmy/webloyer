<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class MaxDeployment extends Model
{
    /** @var array<int, string> */
    protected $fillable = [
        'project_id',
        'number',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'number' => 'integer',
    ];
}
