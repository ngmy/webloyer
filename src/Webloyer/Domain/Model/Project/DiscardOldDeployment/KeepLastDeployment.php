<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project\DiscardOldDeployment;

class KeepLastDeployment
{
    /** @var bool */
    private $value;

    /**
     * @param bool $value
     * @return void
     */
    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function value(): bool
    {
        return $this->value;
    }
}
