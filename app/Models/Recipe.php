<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Recipe
 * @package App\Models
 */
class Recipe extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'recipes';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'body',
    ];

    /**
     * @return BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany('App\Models\Project');
    }

    /**
     * @return Collection
     */
    public function getProjects()
    {
        return $this->projects()->orderBy('name')->get();
    }
}
