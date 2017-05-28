<?php

namespace Ngmy\Webloyer\Webloyer\Application\Setting;

use DB;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;

class SettingService
{
    private $appSettingRepository;

    private $dbSettingRepository;

    private $mailSettingRepository;

    public function __construct(AppSettingRepositoryInterface $appSettingRepository, DbSettingRepositoryInterface $dbSettingRepository, MailSettingRepositoryInterface $mailSettingRepository)
    {
        $this->appSettingRepository = $appSettingRepository;
        $this->dbSettingRepository = $dbSettingRepository;
        $this->mailSettingRepository = $mailSettingRepository;
    }

    public function getMailSetting()
    {
        return $this->mailSettingRepository->mailSetting();
    }

    public function saveMailSetting($mailSettingDriver, $mailSettingFrom, $mailSettingSmtpHost, $mailSettingSmtpPort, $mailSettingSmtpEncryption, $mailSettingSmtpUserName, $mailSettingSmtpPassword, $mailSettingSendmailPath)
    {
        $mailSetting = DB::transaction(function () use ($mailSettingDriver, $mailSettingFrom, $mailSettingSmtpHost, $mailSettingSmtpPort, $mailSettingSmtpEncryption, $mailSettingSmtpUserName, $mailSettingSmtpPassword, $mailSettingSendmailPath) {
            if (is_null($mailSettingSmtpEncryption)) {
                $mailSettingSmtpEncryption = null;
            } else {
                $mailSettingSmtpEncryption = new MailSettingSmtpEncryption($mailSettingSmtpEncryption);
            }

            $mailSetting = new MailSetting(
                new MailSettingDriver($mailSettingDriver),
                $mailSettingFrom,
                $mailSettingSmtpHost,
                $mailSettingSmtpPort,
                $mailSettingSmtpUserName,
                $mailSettingSmtpPassword,
                $mailSettingSendmailPath,
                $mailSettingSmtpEncryption
            );
            return $this->mailSettingRepository->save($mailSetting);
        });

        return $mailSetting;
    }

    public function getAppSetting()
    {
        return $this->appSettingRepository->appSetting();
    }

    public function saveAppSetting($appSettingUrl)
    {
        $appSetting = new AppSetting(
            $appSettingUrl
        );
        return $this->appSettingRepository->save($appSetting);
    }

    public function getDbSetting()
    {
        return $this->dbSettingRepository->dbSetting();
    }

    public function saveDbSetting($dbSettingDriver, $dbSettingHost, $dbSettingDatabase, $dbSettingUserName, $dbSettingPassword)
    {
        $dbSetting = new DbSetting(
            new DbSettingDriver($dbSettingDriver),
            $dbSettingHost,
            $dbSettingDatabase,
            $dbSettingUserName,
            $dbSettingPassword
        );
        return $this->dbSettingRepository->save($dbSetting);
    }
}
