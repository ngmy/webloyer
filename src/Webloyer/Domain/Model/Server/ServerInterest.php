<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Server;

/**
 * @codeCoverageIgnore
 */
interface ServerInterest
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
     * @param string|null $description
     * @return void
     */
    public function informDescription(?string $description): void;
    /**
     * @param string $body
     * @return void
     */
    public function informBody(string $body): void;
}
