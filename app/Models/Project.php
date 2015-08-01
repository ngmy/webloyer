<?php namespace App\Models;

class Project extends BaseModel {

	protected $table = 'projects';

	protected $fillable = [
		'name',
		'recipe_id',
		'stage',
		'repository',
		'server_id',
	];

	public function setStageAttribute($value)
	{
		$this->attributes['stage'] = $this->nullIfBlank($value);
	}

}
