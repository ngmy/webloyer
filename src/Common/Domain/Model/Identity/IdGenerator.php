<?php

declare(strict_types=1);

namespace Common\Domain\Model\Identity;

/**
 * @codeCoverageIgnore
 */
interface IdGenerator
{
    /**
     * @return string
     */
    public function generate(): string;
}
