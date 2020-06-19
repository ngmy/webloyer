<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Server;

class NullServer extends Server
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
    public function id(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function description(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function body(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function changeName(ServerName $name): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changeDescription(ServerDescription $description): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function changeBody(ServerBody $body): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(ServerInterest $interest): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
    {
        return false;
    }

    /**
     * @return void
     */
    private function __construct()
    {
    }
}
