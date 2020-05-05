<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Repositories\Setting\Mail;

use Webloyer\Domain\Model\Setting\Mail;
use Webloyer\Infra\Db\Eloquents\Setting\Setting as SettingOrm;

class DbMailSettingRepository implements Mail\MailSettingRepository
{
    /**
     * @return Mail\MailSettings
     */
    public function findAll(): Mail\MailSettings
    {
        $mailSettingOrm = SettingOrm::mailSetting()->first();

        if (is_null($mailSettingOrm)) {
            return Mail\MailSettings::empty();
        }

        return $mailSettingOrm->toMailSettingEntities();
    }
}
