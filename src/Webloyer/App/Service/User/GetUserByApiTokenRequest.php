<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

class GetUserByApiTokenRequest
{
    /** @var string */
    private $apiToken;

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
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
