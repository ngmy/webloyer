<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use Ngmy\Webloyer\Common\Enum\EnumTrait;

class Direction
{
    use EnumTrait;

    const ENUM = [
        'asc'  => 'asc',
        'desc' => 'desc',
    ];
}
