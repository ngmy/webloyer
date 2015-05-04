<?php namespace App\Services\Form\Project;

use App\Services\Validation\AbstractLaravelValidator;

class ProjectFormLaravelValidator extends AbstractLaravelValidator {

	protected $rules = [
		'name'        => 'required',
		'recipe_path' => 'required',
	];

}
