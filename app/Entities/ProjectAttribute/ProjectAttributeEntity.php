<?php

namespace App\Entities\ProjectAttribute;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;

class ProjectAttributeEntity
{
    /**
     * @Type("string")
     * @Accessor(getter="getDeployPath",setter="setDeployPath")
     * @SerializedName("deploy_path")
     */
    protected $deployPath;

    public function getDeployPath()
    {
        return $this->deployPath;
    }

    public function setDeployPath($deployPath)
    {
        $this->deployPath = $deployPath;
    }
}
