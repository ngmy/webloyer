<?php namespace App\Models;

class MaxDeployment extends BaseModel {

	protected $table = 'max_deployments';

	protected $fillable = ['project_id', 'number'];

	protected $casts = [
		'number' => 'integer',
	];

}
