<?php namespace App\Services\Form\Project;

use App\Services\Validation\AbstractLaravelValidator;

class ProjectFormLaravelValidator extends AbstractLaravelValidator {

	protected $rules = [
		'name'       => 'required',
		'recipe_id'  => 'required|exists:recipes,id',
		'stage'      => 'required',
		'server_id'  => 'required|exists:servers,id',
		'repository' => 'required|url',
	];

}
