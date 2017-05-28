<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\Role;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\AbstractEntity;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;

class Role extends AbstractEntity
{
    private $roleId;

    private $name;

    private $slug;

    private $description;

    public function __construct(RoleId $roleId, $name, RoleSlug $slug, $description)
    {
        $this->setRoleId($roleId);
        $this->setName($name);
        $this->setSlug($slug);
        $this->setDescription($description);
    }

    public function roleId()
    {
        return $this->roleId;
    }

    public function name()
    {
        return $this->name;
    }

    public function slug()
    {
        return $this->slug;
    }

    public function description()
    {
        return $this->description;
    }

    public function equals($object)
    {
        $equalObjects = false;

        if (!is_null($object) && $object instanceof self) {
            $equalObjects = $this->roleId()->equals($object->roleId());
        }

        return $equalObjects;
    }

    private function setRoleId(RoleId $roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    private function setSlug(RoleSlug $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    private function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
