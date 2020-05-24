<?php

declare(strict_types=1);

namespace Common\Domain\Model\Timestamp;

use DateTimeImmutable;

trait Timestampable
{
    /** @var DateTimeImmutable|null */
    private $createdAt;
    /** @var DateTimeImmutable|null */
    private $updatedAt;

    /**
     * @return string|null
     */
    public function createdAt(): ?string
    {
        return isset($this->createdAt) ? $this->createdAt->format('Y-m-d H:i:s') : null;
    }

    /**
     * @return string|null
     */
    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->format('Y-m-d H:i:s') : null;
    }

    /**
     * @param DateTimeImmutable $createdAt
     * @return self
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     *
     * @param DateTimeImmutable $updatedAt
     * @return self
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
