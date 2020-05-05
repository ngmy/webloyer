<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use DateTimeImmutable;

class DeploymentFinishDate
{
    /** @var DateTimeImmutable */
    private $value;

    public static function of(string $value): self
    {
        return new self(new DateTimeImmutable($value));
    }

    public static function now(): self
    {
        return new self(new DateTimeImmutable());
    }

    /**
     * @param DateTimeImmutable $value
     * @return void
     */
    public function __construct(DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }
}
