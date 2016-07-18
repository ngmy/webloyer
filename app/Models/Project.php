<?php

namespace App\Models;

class Project extends BaseModel
{
    protected $table = 'projects';

    protected $fillable = [
        'name',
        'stage',
        'repository',
        'server_id',
        'email_notification_recipient',
    ];

    public function setStageAttribute($value)
    {
        $this->attributes['stage'] = $this->nullIfBlank($value);
    }

    public function setEmailNotificationRecipientAttribute($value)
    {
        $this->attributes['email_notification_recipient'] = $this->nullIfBlank($value);
    }

    public function maxDeployment()
    {
        return $this->hasOne('App\Models\MaxDeployment');
    }

    public function projectAttributes()
    {
        return $this->hasMany('App\Models\ProjectAttribute');
    }

    public function deployments()
    {
        return $this->hasMany('App\Models\Deployment');
    }

    public function recipes()
    {
        return $this->belongsToMany('App\Models\Recipe');
    }

    public function getMaxDeployment()
    {
        return $this->maxDeployment()->lockForUpdate()->first();
    }

    public function getLastDeployment()
    {
        return $this->deployments()->orderBy('number', 'desc')->first();
    }

    public function getDeploymentByNumber($number)
    {
        return $this->deployments()->where('number', $number)->first();
    }

    public function getDeploymentsByPage($page = 1, $limit = 10)
    {
        return $this->deployments()
            ->orderBy('deployments.created_at', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);
    }

    public function getProjectAttributeByName($name)
    {
        return $this->projectAttributes()
            ->where('name', $name)
            ->first();
    }

    public function getProjectAttributes()
    {
        return $this->projectAttributes()->get();
    }

    public function getRecipes()
    {
        return $this->recipes()->orderBy('recipe_order')->get();
    }

    public function addMaxDeployment(array $data = [])
    {
        return $this->maxDeployment()->create($data);
    }

    public function addDeployment(array $data)
    {
        return $this->deployments()->create($data);
    }

    public function updateDeployment(array $data)
    {
        return $this->deployments()
            ->where('number', $data['number'])
            ->update($data);
    }

    public function addProjectAttribute(array $data)
    {
        return $this->projectAttributes()->create($data);
    }

    public function deleteProjectAttributes()
    {
        return $this->projectAttributes()->delete();
    }

    public function syncRecipes(array $data)
    {
        foreach ($data as $i => $recipeId) {
            $syncRecipeIds[$recipeId] = ['recipe_order' => $i + 1];
        }

        return $this->recipes()->sync($syncRecipeIds);
    }

    public function updateMaxDeployment(array $data)
    {
        return $this->maxDeployment()->update($data);
    }
}
