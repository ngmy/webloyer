<?php

declare(strict_types=1);

namespace Webloyer\App\Recipe\Commands;

class CreateRecipeCommand
{
    /** @var string */
    private $name;
    /** @var string|null */
    private $description;
    /** @var string */
    private $body;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $body
     * @return self
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }
}
