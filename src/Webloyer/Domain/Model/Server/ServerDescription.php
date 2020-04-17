<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Server;

class ServerDescription
{
    /** @var string|null */
    private $value;

    /**
     * @param string|null $value
     * @return void
     */
    public function __construct(?string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function value(): ?string
    {
        return $this->value;
    }
}
