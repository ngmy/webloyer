<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment
 *
 * @property int $id
 * @property int $project_id
 * @property int $number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MaxDeployment extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'project_id',
        'number',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'number' => 'integer',
    ];
}
