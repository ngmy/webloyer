<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\Role;

use Ngmy\Webloyer\Common\Enum\EnumTrait;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\AbstractValueObject;

final class RoleSlug extends AbstractValueObject
{
    use EnumTrait;

    const ENUM = [
        'administrator' => 'administrator',
        'developer'     => 'developer',
        'operator'      => 'operator',
    ];

    public function equals($object)
    {
        return $object == $this;
    }
}
