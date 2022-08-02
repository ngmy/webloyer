<?php
declare(strict_types=1);

namespace App\Entities\ProjectAttribute;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ProjectAttributeEntity
 * @package App\Entities\ProjectAttribute
 */
class ProjectAttributeEntity
{
    /**
     * @Type("string")
     * @Accessor(getter="getDeployPath",setter="setDeployPath")
     * @SerializedName("deploy_path")
     */
    protected $deployPath;

    /**
     * @return string
     */
    public function getDeployPath()
    {
        return $this->deployPath;
    }

    /**
     * @param string $deployPath
     */
    public function setDeployPath($deployPath)
    {
        $this->deployPath = $deployPath;
    }
}
