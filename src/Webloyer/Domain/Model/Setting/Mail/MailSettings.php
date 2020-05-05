<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Setting\Mail;

class MailSettings
{
    /** @var array<int, MailSetting> */
    private $mailSettings;

    public static function empty(): self
    {
        return new self(...[]);
    }

    public function __construct(MailSetting ...$mailSettings)
    {
        $this->mailSettings = $mailSettings;
    }
}
