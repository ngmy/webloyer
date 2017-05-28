<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;

interface MailSettingRepositoryInterface
{
    public function mailSetting();

    public function save(MailSetting $mailSetting);
}
