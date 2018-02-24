<?php

namespace Ngmy\Webloyer\Webloyer\Application\Setting;

use Mockery;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use TestCase;
use Tests\Helpers\MockeryHelper;

class SettingServiceTest extends TestCase
{
    use MockeryHelper;

    private $settingService;

    private $appSettingRepository;

    private $dbSettingRepository;

    private $mailSettingRepository;

    private $inputForSaveAppSetting = [
        'appSettingUrl' => '',
    ];

    private $inputForSaveDbSetting = [
        'dbSettingDriver'   => 'mysql',
        'dbSettingHost'     => '',
        'dbSettingDatabase' => '',
        'dbSettingUserName' => '',
        'dbSettingPassword' => '',
    ];

    private $inputForSaveMailSetting = [
        'mailSettingDriver'         => 'smtp',
        'mailSettingFrom'           => [],
        'mailSettingSmtpHost'       => '',
        'mailSettingSmtpPort'       => '',
        'mailSettingSmtpEncryption' => null,
        'mailSettingSmtpUserName'   => '',
        'mailSettingSmtpPassword'   => '',
        'mailSettingSendmailPath'   => '',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->appSettingRepository = $this->mock(AppSettingRepositoryInterface::class);
        $this->dbSettingRepository = $this->mock(DbSettingRepositoryInterface::class);
        $this->mailSettingRepository = $this->mock(MailSettingRepositoryInterface::class);
        $this->settingService = new SettingService(
            $this->appSettingRepository,
            $this->dbSettingRepository,
            $this->mailSettingRepository
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function tests_Should_GetAppSetting()
    {
        $expectedResult = true;
        $this->appSettingRepository
            ->shouldReceive('appSetting')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->settingService->getAppSetting();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function tests_Should_SaveAppSetting()
    {
        $this->appSettingRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveAppSetting);
                $appSetting = new AppSetting(
                    $appSettingUrl
                );
                return $arg == $appSetting;
            }))
            ->once();

        extract($this->inputForSaveAppSetting);

        $this->settingService->saveAppSetting(
            $appSettingUrl
        );

        $this->assertTrue(true);
    }

    public function tests_Should_GetDbSetting()
    {
        $expectedResult = true;
        $this->dbSettingRepository
            ->shouldReceive('dbSetting')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->settingService->getDbSetting();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function tests_Should_SaveDbSetting()
    {
        $this->dbSettingRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveDbSetting);
                $dbSetting = new DbSetting(
                    new DbSettingDriver($dbSettingDriver),
                    $dbSettingHost,
                    $dbSettingDatabase,
                    $dbSettingUserName,
                    $dbSettingPassword
                );
                return $arg == $dbSetting;
            }))
            ->once();

        extract($this->inputForSaveDbSetting);

        $this->settingService->saveDbSetting(
            $dbSettingDriver,
            $dbSettingHost,
            $dbSettingDatabase,
            $dbSettingUserName,
            $dbSettingPassword
        );

        $this->assertTrue(true);
    }

    public function tests_Should_GetMailSetting()
    {
        $expectedResult = true;
        $this->mailSettingRepository
            ->shouldReceive('mailSetting')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->settingService->getMailSetting();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function tests_Should_SaveMailSetting_When_MailSettingEncryptionIsNull()
    {
        $this->checkSaveMailSetting();
    }

    public function tests_Should_SaveMailSetting_When_MailSettingEncryptionIsNotNull()
    {
        $this->inputForSaveMailSetting['mailSettingSmtpEncryption'] = 'tls';

        $this->checkSaveMailSetting();
    }

    private function checkSaveMailSetting()
    {
        $this->mailSettingRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveMailSetting);
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
                return $arg == $mailSetting;
            }))
            ->once();

        extract($this->inputForSaveMailSetting);

        $this->settingService->saveMailSetting(
            $mailSettingDriver,
            $mailSettingFrom,
            $mailSettingSmtpHost,
            $mailSettingSmtpPort,
            $mailSettingSmtpEncryption,
            $mailSettingSmtpUserName,
            $mailSettingSmtpPassword,
            $mailSettingSendmailPath
        );

        $this->assertTrue(true);
    }
}
