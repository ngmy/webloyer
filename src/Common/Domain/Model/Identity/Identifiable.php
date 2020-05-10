<?php

declare(strict_types=1);

namespace Common\Domain\Model\Identity;

trait Identifiable
{
    /** @var int */
    private $surrogateId = -1;

    /**
     * @return int
     */
    public function surrogateId(): int
    {
        return $this->surrogateId;
    }

    /**
     * @param int $surrogateId
     * @return self
     */
    public function setSurrogateId(int $surrogateId): self
    {
        $this->surrogateId = $surrogateId;
        return $this;
    }
}
