<?php
declare(strict_types=1);

namespace App\Services\Deployment;

/**
 * Class DeployerFile
 * @package App\Services\Deployment
 */
class DeployerFile
{
    /**
     * @var string
     */
    protected string $baseName;

    /**
     * @var string
     */
    protected string $fullPath;

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
     * @return DeployerFile $this
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
     * @return DeployerFile $this
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
        return $this;
    }
}
