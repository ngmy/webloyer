<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Setting\Mail;

/**
 * @codeCoverageIgnore
 */
interface MailSettingRepository
{
    /**
     * @return MailSettings
     */
    public function findAll(): MailSettings;
}
