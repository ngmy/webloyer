<?php

declare(strict_types=1);

namespace Common\Infra\Domain\Model\Identity;

use Common\Domain\Model\Identity\IdGenerator;
use Str;

class UuidIdGenerator implements IdGenerator
{
    public function generate(): string
    {
        return Str::orderedUuid()->toString();
    }
}
