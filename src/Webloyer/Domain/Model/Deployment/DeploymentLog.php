<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

class DeploymentLog
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     * @return void
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    public function append(string $value): self
    {
        return new self($this->value . $value);
    }
}
