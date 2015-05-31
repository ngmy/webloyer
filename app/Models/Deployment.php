<?php namespace App\Models;

use Robbo\Presenter\PresentableInterface;

class Deployment extends BaseModel implements PresentableInterface {

	protected $table = 'deployments';

	protected $fillable = [
		'project_id',
		'number',
		'task',
		'status',
		'message',
		'user_id',
	];

	protected $casts = [
		'number' => 'integer',
		'status' => 'integer',
	];

	/**
	 * Return a created presenter.
	 *
	 * @return \Robbo\Presenter\Presenter
	 */
	public function getPresenter()
	{
		return new DeploymentPresenter($this);
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

}
