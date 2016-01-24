<?php

namespace App\Services\Deployment;

use Storage;

use Illuminate\Database\Eloquent\Model;

class DeployerServerListFileBuilder implements DeployerFileBuilderInterface
{
    protected $deployerFile;

    protected $server;

    public function __construct(Model $server)
    {
        $this->deployerFile = new DeployerFile;
        $this->server = $server;
    }

    public function __destruct()
    {
        Storage::delete($this->deployerFile->getBaseName());
    }

    /**
     * Set a server list file path info.
     *
     * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
     */
    public function pathInfo()
    {
        $id = md5(uniqid(rand(), true));

        $baseName = "server_$id.yml";
        $fullPath = storage_path("app/$baseName");

        $this->deployerFile->setBaseName($baseName);
        $this->deployerFile->setFullPath($fullPath);

        return $this;
    }

    /**
     * Put a server list file.
     *
     * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
     */
    public function put()
    {
        $baseName = $this->deployerFile->getBaseName();
        $contents = $this->server->body;

        Storage::put($baseName, $contents);

        return $this;
    }

    /**
     * Get a server list file instance.
     *
     * @return \App\Services\Deployment\DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }
}
