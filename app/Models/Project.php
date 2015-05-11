<?php namespace App\Models;

class Project extends BaseModel {

	protected $table = 'projects';

	protected $fillable = ['name', 'recipe_path', 'stage'];

	public function setStageAttribute($value)
	{
		$this->attributes['stage'] = $this->nullIfBlank($value);
	}

}
