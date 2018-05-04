<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe as EntityRecipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
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

    /**
     * Convert an Eloquent object into an entity.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe
     */
    public function toEntity()
    {
        return new EntityRecipe(
            new RecipeId($this->id),
            $this->name,
            $this->description,
            $this->body,
            $this->projects->map(function ($project) {
                return new ProjectId($project->id);
            })->all(),
            $this->created_at,
            $this->updated_at
        );
    }
}
