<?php
declare(strict_types=1);

namespace App\Services\Deployment;

/**
 * Interface DeployCommanderInterface
 * @package App\Services\Deployment
 */
interface DeployCommanderInterface
{
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

    /**
     * Give the command to unlock
     *
     * @param mixed $deployment
     * @return boolean
     */
    public function unlock($deployment);
}
