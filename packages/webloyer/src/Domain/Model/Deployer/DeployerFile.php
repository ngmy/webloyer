<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

class DeployerFile
{
    private $baseName;

    private $fullPath;

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
     * @return $this
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
     * @return $this
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;

        return $this;
    }
}
