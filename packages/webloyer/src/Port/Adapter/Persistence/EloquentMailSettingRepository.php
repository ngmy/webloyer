<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Setting as EloquentSetting;

class EloquentMailSettingRepository implements MailSettingRepositoryInterface
{
    private $eloquentSetting;

    /**
     * Create a new repository instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Setting $eloquentSetting
     * @return void
     */
    public function __construct(EloquentSetting $eloquentSetting)
    {
        $this->eloquentSetting = $eloquentSetting;
    }

    public function mailSetting()
    {
        $eloquentSetting = $this->eloquentSetting->where('type', 'mail')->first();

        if (is_null($eloquentSetting)) {
            $driver = MailSettingDriver::smtp();
            $smtpEncryption = MailSettingSmtpEncryption::tls();

            $mailSetting = new MailSetting(
                $driver,
                [
                    'address' => 'webloyer@example.com',
                    'name'    => 'Webloyer',
                ],
                'smtp.mailgun.org',
                587,
                null,
                null,
                '/usr/sbin/sendmail -bs',
                $smtpEncryption
            );
        } else {
            $mailSetting = $eloquentSetting->attributes;
        }

        return $mailSetting;
    }

    public function save(MailSetting $mailSetting)
    {
        $eloquentSetting = $this->eloquentSetting->where('type', 'mail')->first();

        if (is_null($eloquentSetting)) {
            $eloquentSetting = new EloquentSetting();
            $eloquentSetting->type = 'mail';
        }

        $eloquentSetting->attributes = $mailSetting;

        $eloquentSetting->save();

        $mailSetting = $eloquentSetting->attributes;

        return $mailSetting;
    }
}
