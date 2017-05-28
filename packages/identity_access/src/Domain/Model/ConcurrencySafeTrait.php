<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model;

use RuntimeException;

trait ConcurrencySafeTrait
{
    private $concurrencyVersion;

    public function concurrencyVersion()
    {
        return $this->concurrencyVersion;
    }

    public function failWhenConcurrencyViolation($concurrencyVersion)
    {
        if ($concurrencyVersion != $this->concurrencyVersion) {
            throw new RuntimeException('Concurrency Violation: Stale data detected. Entity was already modified.');
        }
    }

    protected function setConcurrencyVersion($concurrencyVersion)
    {
        $this->concurrencyVersion = $concurrencyVersion;
    }
}
