<?php namespace App\Models;

class Project extends BaseModel {

	protected $table = 'projects';

	protected $fillable = [
		'name',
		'stage',
		'repository',
		'server_id',
	];

	public function setStageAttribute($value)
	{
		$this->attributes['stage'] = $this->nullIfBlank($value);
	}

	public function deployments()
	{
		return $this->hasMany('App\Models\Deployment');
	}

	public function recipes()
	{
		return $this->belongsToMany('App\Models\Recipe');
	}

	public function getLastDeployment()
	{
		return $this->deployments->first();
	}

	public function getRecipes()
	{
		return $this->recipes()->orderBy('recipe_order')->get();
	}

}
