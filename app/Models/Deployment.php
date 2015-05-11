<?php namespace App\Models;

class Deployment extends BaseModel {

	protected $table = 'deployments';

	protected $fillable = ['project_id', 'task', 'status', 'message', 'user_id'];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

}
