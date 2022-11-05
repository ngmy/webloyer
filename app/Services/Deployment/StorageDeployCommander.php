<?php
declare(strict_types=1);

namespace App\Services\Deployment;

use Illuminate\Support\Facades\Storage;

/**
 * Class StorageDeployCommander
 * @package App\Services\Deployment
 */
class StorageDeployCommander implements DeployCommanderInterface
{
    /**
     * Give the command to deploy
     *
     * @param mixed $deployment
     * @return bool
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
     * @return bool
     */
    public function rollback($deployment)
    {
        if (!Storage::put('rollback.json', $deployment)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Give the command to unlock
     *
     * @param mixed $deployment
     * @return bool
     */
    public function unlock($deployment)
    {
        if (!Storage::put('unlock.json', $deployment)) {
            return false;
        } else {
            return true;
        }
    }
}
