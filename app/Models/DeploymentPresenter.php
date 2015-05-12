<?php namespace App\Models;

use Robbo\Presenter\Presenter;

class DeploymentPresenter extends Presenter {

	public function status()
	{
		if (!isset($this->status)) {
			return '<span></span>';
		} elseif ($this->status === 0) {
			return '<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>';
		} else {
			return '<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>';
		}
	}

}
