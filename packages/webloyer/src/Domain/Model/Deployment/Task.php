<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Common\Enum\EnumTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class Task extends AbstractValueObject
{
    use EnumTrait;

    const ENUM = [
        'deploy'   => 'deploy',
        'rollback' => 'rollback',
    ];

    public function equals($object)
    {
        return $object == $this;
    }
}
