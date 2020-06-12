<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Role;

class RoleView
{
    /** @var string */
    private $slug;
    /** @var string */
    private $name;

    public function __construct(string $slug, string $name)
    {
        $this->slug = $slug;
        $this->name = $name;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }
}
