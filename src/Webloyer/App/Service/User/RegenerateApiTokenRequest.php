<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

class RegenerateApiTokenRequest
{
    /** @var string */
    private $id;
    /** @var string */
    private $apiToken;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
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

    /**
     * @param string $apiToken
     * @return self
     */
    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;
        return $this;
    }
}
