<?php

declare(strict_types=1);

namespace Common\Infra\Domain\Model\Identity;

use Common\Domain\Model\Identity\IdGenerator;
use Illuminate\Support\Str;

class UuidIdGenerator implements IdGenerator
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return Str::orderedUuid()->toString();
    }
}
