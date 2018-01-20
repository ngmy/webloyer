<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\NullMailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use TestCase;

class MailSettingTest extends TestCase
{
    public function test_Should_SetAndGetDriver()
    {
        $mailSetting = new MailSetting;

        $mailSetting->setDriver('smtp');

        $driver = $mailSetting->getDriver();

        $this->assertEquals('smtp', $driver);
    }

    public function test_Should_SetAndGetFrom()
    {
        $mailSetting = new MailSetting;

        $from['address'] = 'from_address@example.com';
        $from['name']    = 'from_name';

        $mailSetting->setFrom($from);

        $retFrom = $mailSetting->getFrom();

        $this->assertEquals($from, $retFrom);
    }

    public function test_Should_SetAndGetSmtpHost()
    {
        $mailSetting = new MailSetting;

        $mailSetting->setSmtpHost('localhost');

        $smtpHost = $mailSetting->getSmtpHost();

        $this->assertEquals('localhost', $smtpHost);
    }

    public function test_Should_SetAndGetSmtpPort()
    {
        $mailSetting = new MailSetting;

        $mailSetting->setSmtpPort(587);

        $smtpPort = $mailSetting->getSmtpPort();

        $this->assertEquals(587, $smtpPort);
    }

    public function test_Should_SetAndGetSmtpEncryption()
    {
        $mailSetting = new MailSetting;

        $mailSetting->setSmtpEncryption('tls');

        $smtpEncryption = $mailSetting->getSmtpEncryption();

        $this->assertEquals('tls', $smtpEncryption);
    }

    public function test_Should_SetAndGetSmtpUsername()
    {
        $mailSetting = new MailSetting;

        $mailSetting->setSmtpUsername('username@example.com');

        $smtpUsername = $mailSetting->getSmtpUsername();

        $this->assertEquals('username@example.com', $smtpUsername);
    }

    public function test_Should_SetAndGetSmtpPassword()
    {
        $mailSetting = new MailSetting;

        $mailSetting->setSmtpPassword('password');

        $smtpPassword = $mailSetting->getSmtpPassword();

        $this->assertEquals('password', $smtpPassword);
    }

    public function test_Should_SetAndGetSendmailPath()
    {
        $mailSetting = new MailSetting;

        $mailSetting->setSendmailPath('/usr/sbin/sendmail -bs');

        $sendmailPath = $mailSetting->getSendmailPath();

        $this->assertEquals('/usr/sbin/sendmail -bs', $sendmailPath);
    }
}
