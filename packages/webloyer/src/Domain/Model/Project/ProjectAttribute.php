<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class ProjectAttribute extends AbstractValueObject
{
    /**
     * @Type("string")
     * @Accessor(getter="deployPath",setter="setDeployPath")
     * @SerializedName("deploy_path")
     */
    private $deployPath;

    public function __construct($deployPath)
    {
        $this->setDeployPath($deployPath);
    }

    public function deployPath()
    {
        return $this->deployPath;
    }

    public function setDeployPath($deployPath)
    {
        $this->deployPath = $deployPath;
    }

    public function equals($object)
    {
        return $object == $this;
    }
}
