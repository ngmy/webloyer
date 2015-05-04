<?php namespace App\Services\Deployment;

interface DeployCommanderInterface {

	/**
	 * Give the command to deploy
	 *
	 * @param mixed $deployment
	 * @return boolean
	 */
	public function deploy($deployment);

	/**
	 * Give the command to rollback
	 *
	 * @param mixed $deployment
	 * @return boolean
	 */
	public function rollback($deployment);

}
