<?php

namespace App\Services\Deployment;

use Storage;

class StorageDeployCommander implements DeployCommanderInterface
{
    /**
     * Give the command to deploy
     *
     * @param mixed $deployment
     * @return boolean
     */
    public function deploy($deployment)
    {
        if (!Storage::put('deploy.json', $deployment)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Give the command to rollback
     *
     * @param mixed $deployment
     * @return boolean
     */
    public function rollback($deployment)
    {
        if (!Storage::put('rollback.json', $deployment)) {
            return false;
        } else {
            return true;
        }
    }
}
