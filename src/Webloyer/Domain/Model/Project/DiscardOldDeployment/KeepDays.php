<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project\DiscardOldDeployment;

class KeepDays
{
    /** @var int */
    private $value;

    /**
     * @param int $value
     * @return void
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }
}
