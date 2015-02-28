<?php namespace App\Services\Form\Deployment;

use App\Services\Validation\ValidableInterface;
use App\Services\Deployment\DeployCommanderInterface;
use App\Repositories\Deployment\DeploymentInterface;

class DeploymentForm {

	protected $validator;

	protected $deployment;

	protected $deployCommander;

	/**
	 * Create a new form service instance.
	 *
	 * @param \App\Services\Validation\ValidableInterface       $validator
	 * @param \App\Repositories\Deployment\DeploymentInterface  $deployment
	 * @param \App\Services\Deployment\DeployCommanderInterface $deployCommander
	 * @return void
	 */
	public function __construct(ValidableInterface $validator, DeploymentInterface $deployment, DeployCommanderInterface $deployCommander)
	{
		$this->validator       = $validator;
		$this->deployment      = $deployment;
		$this->deployCommander = $deployCommander;
	}

	/**
	 * Create a new deployment.
	 *
	 * @param array $input Data to create a deployment
	 * @return boolean
	 */
	public function save(array $input)
	{
		if (!$this->valid($input)) {
			return false;
		}

		$deployment = $this->deployment->create($input);

		if (!$deployment) {
			return false;
		}

		return $this->deployCommander->$input['task']($deployment);
	}

	/**
	 * Return validation errors.
	 *
	 * @return array
	 */
	public function errors()
	{
		return $this->validator->errors();
	}

	/**
	 * Test whether form validator passes.
	 *
	 * @return boolean
	 */
	protected function valid(array $input)
	{
		return $this->validator->with($input)->passes();
	}

}
