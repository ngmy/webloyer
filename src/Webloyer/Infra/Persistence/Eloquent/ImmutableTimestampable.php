<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent;

use Carbon\CarbonImmutable;

trait ImmutableTimestampable
{
    /**
     * @param string $value
     * @return CarbonImmutable
     */
    public function getCreatedAtAttribute(string $value): CarbonImmutable
    {
        return new CarbonImmutable($value);
    }

    /**
     * @param string $value
     * @return CarbonImmutable
     */
    public function getUpdatedAtAttribute(string $value): CarbonImmutable
    {
        return new CarbonImmutable($value);
    }
}
