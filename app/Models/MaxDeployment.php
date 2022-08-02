<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Class MaxDeployment
 * @package App\Models
 */
class MaxDeployment extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'max_deployments';

    /**
     * @var array
     */
    protected $fillable = ['project_id', 'number'];

    /**
     * @var array
     */
    protected $casts = [
        'number' => 'integer',
    ];
}
