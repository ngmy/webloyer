<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project\Webhook\Github;

use Webloyer\Domain\Model\Project\ProjectInterest;
use Webloyer\Domain\Model\User\UserId;

class GithubWebhook
{
    /** @var GithubWebhookSecret|null */
    private $secret;
    /** @var UserId|null */
    private $executor;

    /**
     * @param string|null $secret
     * @param string|null $executor
     * @return self
     */
    public static function of(
        ?string $secret,
        ?string $executor
    ): self {
        return new self(
            isset($secret) ? new GithubWebhookSecret($secret) : null,
            isset($executor) ? new UserId($executor) : null
        );
    }

    /**
     * @param GithubWebhookSecret|null $secret
     * @param UserId|null              $executor
     * @return void
     */
    public function __construct(
        ?GithubWebhookSecret $secret,
        ?UserId $executor
    ) {
        $this->secret = $secret;
        $this->executor = $executor;
    }

    /**
     * @return string|null
     */
    public function secret(): ?string
    {
        return isset($this->secret) ? $this->secret->value() : null;
    }

    /**
     * @return string|null
     */
    public function executor(): ?string
    {
        return isset($this->executor) ? $this->executor->value() : null;
    }

    /**
     * @param ProjectInterest $interest
     * @return void
     */
    public function provide(ProjectInterest $interest): void
    {
        $interest->informGithubWebhookSecret($this->secret());
        $interest->informGithubWebhookExecutor($this->executor());
    }
}
