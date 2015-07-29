<?php namespace App\Models;

class Project extends BaseModel {

	protected $table = 'projects';

	protected $fillable = [
		'name',
		'recipe_id',
		'stage',
		'repository',
		'servers',
	];

	public function setStageAttribute($value)
	{
		$this->attributes['stage'] = $this->nullIfBlank($value);
	}

}
