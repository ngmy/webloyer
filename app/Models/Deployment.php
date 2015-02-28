<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model {

	protected $table = 'deployments';

	protected $fillable = ['project_id', 'task', 'status', 'message', 'user_id'];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

}
