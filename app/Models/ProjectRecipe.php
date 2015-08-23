<?php namespace App\Models;

class ProjectRecipe extends BaseModel {

	public $timestamps = false;

	protected $table = 'project_recipe';

	protected $fillable = [
		'project_id',
		'recipe_id',
		'recipe_order',
	];

}
