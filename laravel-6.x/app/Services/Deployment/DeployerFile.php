<?php

namespace App\Services\Deployment;

class DeployerFile
{
    protected $baseName;

    protected $fullPath;

    /**
     * Get a base name.
     *
     * @return string
     */
    public function getBaseName()
    {
        return $this->baseName;
    }

    /**
     * Get a full path.
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * Set a base name.
     *
     * @param string Base name
     * @return \App\Services\Deployment\DeployerFile $this
     */
    public function setBaseName($baseName)
    {
        $this->baseName = $baseName;

        return $this;
    }

    /**
     * Set a full path.
     *
     * @param string Full path
     * @return \App\Services\Deployment\DeployerFile $this
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;

        return $this;
    }
}
