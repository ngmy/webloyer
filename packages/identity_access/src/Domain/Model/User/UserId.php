<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\AbstractValueObject;

final class UserId extends AbstractValueObject
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
