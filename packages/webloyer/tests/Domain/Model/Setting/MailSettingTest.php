<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\NullMailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use TestCase;

class MailSettingTest extends TestCase
{
    public function test_Should_GetDriver()
    {
        $expectedResult = MailSettingDriver::smtp();

        $mailSetting = $this->createMailSetting([
            'driver' => $expectedResult->value(),
        ]);

        $actualResult = $mailSetting->driver();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDriverValue()
    {
        $expectedResult = 'smtp';

        $mailSetting = $this->createMailSetting([
            'driver' => $expectedResult,
        ]);

        $actualResult = $mailSetting->driverValue();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetFrom()
    {
        $expectedResult = [
            'address' => 'from_address@example.com',
            'name'    => 'from_name',
        ];

        $mailSetting = $this->createMailSetting([
            'from' => $expectedResult,
        ]);

        $actualResult = $mailSetting->from();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpHost()
    {
        $expectedResult = 'localhost';

        $mailSetting = $this->createMailSetting([
            'smtpHost' => $expectedResult,
        ]);

        $actualResult = $mailSetting->smtpHost();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpPort()
    {
        $expectedResult = '587';

        $mailSetting = $this->createMailSetting([
            'smtpPort' => $expectedResult,
        ]);

        $actualResult = $mailSetting->smtpPort();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpEncryption_When_NotNull()
    {
        $expectedResult = MailSettingSmtpEncryption::ssl();

        $mailSetting = $this->createMailSetting([
            'smtpEncryption' => $expectedResult->value(),
        ]);

        $actualResult = $mailSetting->smtpEncryption();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpEncryption_When_Null()
    {
        $expectedResult = NullMailSettingSmtpEncryption::getInstance();

        $mailSetting = $this->createMailSetting([
            'smtpEncryption' => $expectedResult->value(),
        ]);

        $actualResult = $mailSetting->smtpEncryption();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpEncryptionValue_When_NotNull()
    {
        $expectedResult = 'ssl';

        $mailSetting = $this->createMailSetting([
            'smtpEncryption' => $expectedResult,
        ]);

        $actualResult = $mailSetting->smtpEncryptionValue();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpEncryptionValue_When_Null()
    {
        $expectedResult = null;

        $mailSetting = $this->createMailSetting([
            'smtpEncryption' => $expectedResult,
        ]);

        $actualResult = $mailSetting->smtpEncryptionValue();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpUserName()
    {
        $expectedResult = 'username@example.com';

        $mailSetting = $this->createMailSetting([
            'smtpUserName' => $expectedResult,
        ]);

        $actualResult = $mailSetting->smtpUserName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSmtpPassword()
    {
        $expectedResult = 'password';

        $mailSetting = $this->createMailSetting([
            'smtpPassword' => $expectedResult,
        ]);

        $actualResult = $mailSetting->smtpPassword();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetSendmailPath()
    {
        $expectedResult = '/usr/sbin/sendmail -bs';

        $mailSetting = $this->createMailSetting([
            'sendmailPath' => $expectedResult,
        ]);

        $actualResult = $mailSetting->sendmailPath();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetDriver()
    {
        $driver = MailSettingDriver::php();

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setDriver($driver);

        $expectedResult = $this->createMailSetting([
            'driver' => $driver->value(),
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetDriverValue()
    {
        $driver = 'mail';

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setDriverValue($driver);

        $expectedResult = $this->createMailSetting([
            'driver' => $driver,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetFrom()
    {
        $from = [
            'address' => 'from_address@example.com',
            'name'    => 'from_name',
        ];

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setFrom($from);

        $expectedResult = $this->createMailSetting([
            'from' => $from,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetSmtpHost()
    {
        $smtpHost = 'localhost';

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setSmtpHost($smtpHost);

        $expectedResult = $this->createMailSetting([
            'smtpHost' => $smtpHost,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetSmtpPort()
    {
        $smtpPort = 587;

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setSmtpPort($smtpPort);

        $expectedResult = $this->createMailSetting([
            'smtpPort' => $smtpPort,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetSmtpEncryption()
    {
        $smtpEncryption = MailSettingSmtpEncryption::ssl();

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setSmtpEncryption($smtpEncryption);

        $expectedResult = $this->createMailSetting([
            'smtpEncryption' => $smtpEncryption->value(),
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetSmtpEncryptionValue()
    {
        $smtpEncryption = 'ssl';

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setSmtpEncryptionValue($smtpEncryption);

        $expectedResult = $this->createMailSetting([
            'smtpEncryption' => $smtpEncryption,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetSmtpUserName()
    {
        $smtpUserName = 'username@example.com';

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setSmtpUserName($smtpUserName);

        $expectedResult = $this->createMailSetting([
            'smtpUserName' => $smtpUserName,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetSmtpPassword()
    {
        $smtpPassword = 'password';

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setSmtpPassword($smtpPassword);

        $expectedResult = $this->createMailSetting([
            'smtpPassword' => $smtpPassword,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetSendmailPath()
    {
        $sendmailPath = '/usr/sbin/sendmail -bs';

        $mailSetting = $this->createMailSetting();

        $actualResult = $mailSetting->setSendmailPath($sendmailPath);

        $expectedResult = $this->createMailSetting([
            'sendmailPath' => $sendmailPath,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createMailSetting(array $params = [])
    {
        $driver = 'smtp';
        $from = [
            'address' => '',
            'name' => '',
        ];
        $smtpHost = '';
        $smtpPort = 25;
        $smtpUserName = '';
        $smtpPassword = '';
        $sendmailPath = '';
        $smtpEncryption = 'tls';

        extract($params);

        if (!is_null($smtpEncryption)) {
            $smtpEncryption = new MailSettingSmtpEncryption($smtpEncryption);
        }

        return new MailSetting(
            new MailSettingDriver($driver),
            $from,
            $smtpHost,
            $smtpPort,
            $smtpUserName,
            $smtpPassword,
            $sendmailPath,
            $smtpEncryption
        );
    }
}
