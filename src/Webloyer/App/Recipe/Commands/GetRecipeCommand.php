<?php

declare(strict_types=1);

namespace Webloyer\App\Recipe\Commands;

class GetRecipeCommand
{
    /** @var string */
    private $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
}
