<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Setting\Mail;

class MailSettings
{
    /** @var array<int, MailSetting> */
    private $mailSettings;

    public function __construct(MailSetting ...$mailSettings)
    {
        $this->mailSettings = $mailSettings;
    }
}
