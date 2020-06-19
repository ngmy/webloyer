<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

class NullUserId extends UserId
{
    /** @var self|null */
    private static $instance;

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public function value(): string
    {
        return '';
    }

    /**
     * @return void
     */
    private function __construct()
    {
    }
}
