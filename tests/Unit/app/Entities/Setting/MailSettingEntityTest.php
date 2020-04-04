<?php

namespace Tests\Unit\app\Entities\Setting;

use App\Entities\Setting\MailSettingEntity;
use Tests\TestCase;

class MailSettingEntityTest extends TestCase
{
    public function test_Should_SetAndGetDriver()
    {
        $mailSettingEntity = new MailSettingEntity;

        $mailSettingEntity->setDriver('smtp');

        $driver = $mailSettingEntity->getDriver();

        $this->assertEquals('smtp', $driver);
    }

    public function test_Should_SetAndGetFrom()
    {
        $mailSettingEntity = new MailSettingEntity;

        $from['address'] = 'from_address@example.com';
        $from['name']    = 'from_name';

        $mailSettingEntity->setFrom($from);

        $retFrom = $mailSettingEntity->getFrom();

        $this->assertEquals($from, $retFrom);
    }

    public function test_Should_SetAndGetSmtpHost()
    {
        $mailSettingEntity = new MailSettingEntity;

        $mailSettingEntity->setSmtpHost('localhost');

        $smtpHost = $mailSettingEntity->getSmtpHost();

        $this->assertEquals('localhost', $smtpHost);
    }

    public function test_Should_SetAndGetSmtpPort()
    {
        $mailSettingEntity = new MailSettingEntity;

        $mailSettingEntity->setSmtpPort(587);

        $smtpPort = $mailSettingEntity->getSmtpPort();

        $this->assertEquals(587, $smtpPort);
    }

    public function test_Should_SetAndGetSmtpEncryption()
    {
        $mailSettingEntity = new MailSettingEntity;

        $mailSettingEntity->setSmtpEncryption('tls');

        $smtpEncryption = $mailSettingEntity->getSmtpEncryption();

        $this->assertEquals('tls', $smtpEncryption);
    }

    public function test_Should_SetAndGetSmtpUsername()
    {
        $mailSettingEntity = new MailSettingEntity;

        $mailSettingEntity->setSmtpUsername('username@example.com');

        $smtpUsername = $mailSettingEntity->getSmtpUsername();

        $this->assertEquals('username@example.com', $smtpUsername);
    }

    public function test_Should_SetAndGetSmtpPassword()
    {
        $mailSettingEntity = new MailSettingEntity;

        $mailSettingEntity->setSmtpPassword('password');

        $smtpPassword = $mailSettingEntity->getSmtpPassword();

        $this->assertEquals('password', $smtpPassword);
    }

    public function test_Should_SetAndGetSendmailPath()
    {
        $mailSettingEntity = new MailSettingEntity;

        $mailSettingEntity->setSendmailPath('/usr/sbin/sendmail -bs');

        $sendmailPath = $mailSettingEntity->getSendmailPath();

        $this->assertEquals('/usr/sbin/sendmail -bs', $sendmailPath);
    }
}
