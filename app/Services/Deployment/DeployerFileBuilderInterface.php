<?php namespace App\Services\Deployment;

interface DeployerFileBuilderInterface {

	/**
	 * Set a deployer file path info.
	 *
	 * @return \App\Services\Deployment\DeployerFileBuilderInterface $this
	 */
	public function pathInfo();

	/**
	 * Put a deployer file.
	 *
	 * @return \App\Services\Deployment\DeployerFileBuilderInterface $this
	 */
	public function put();

	/**
	 * Get a deployer file instance.
	 *
	 * @return \App\Services\Deployment\DeployerFile
	 */
	public function getResult();

}
