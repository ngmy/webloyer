<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class ProjectId extends AbstractValueObject
{
    private $id;

    public function __construct($id)
    {
        $this->setId($id);
    }

    public function id()
    {
        return $this->id;
    }

    public function equals($object)
    {
        return $object == $this;
    }

    private function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
