<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

/**
 * @codeCoverageIgnore
 */
interface UserInterest
{
    /**
     * @param string $id
     * @return void
     */
    public function informId(string $id): void;
    /**
     * @param string $name
     * @return void
     */
    public function informName(string $name): void;
    /**
     * @param string $password
     * @return void
     */
    public function informPassword(string $password): void;
    /**
     * @param string $apiToken
     * @return void
     */
    public function informApiToken(string $apiToken): void;
}
