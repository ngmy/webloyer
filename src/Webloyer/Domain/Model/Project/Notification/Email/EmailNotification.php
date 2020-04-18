<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project\Notification\Email;

use Webloyer\Domain\Model\Project\ProjectInterest;

class EmailNotification
{
    /** @var EmailAddress|null */
    private $recipient;

    /**
     * @param string|null $recipient
     * @return self
     */
    public static function of(?string $recipient): self
    {
        return new self(
            isset($recipient) ? new EmailAddress($recipient) : null
        );
    }

    /**
     * @param EmailAddress|null $recipient
     * @return void
     *
     */
    public function __construct(?EmailAddress $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return string|null
     */
    public function recipient(): ?string
    {
        return isset($this->recipient) ? $this->recipient->value() : null;
    }

    /**
     * @param ProjectInterest $interest
     * @return void
     */
    public function provide(ProjectInterest $interest): void
    {
        $interest->informEmailNotificationRecipient($this->recipient());
    }
}
