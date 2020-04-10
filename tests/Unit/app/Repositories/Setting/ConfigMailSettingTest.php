<?php

namespace Tests\Unit\app\Repositories\Setting;

use App\Repositories\Setting\ConfigMailSetting;
use App\Services\Config\DotenvReader;
use App\Services\Config\DotenvWriter;
use App\Services\Filesystem\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use Tests\TestCase;

class ConfigMailSettingTest extends TestCase
{
    protected $rootDir;

    public function setUp(): void
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function testShouldGetAllMailSettings()
    {
        $config = <<<EOF
MAIL_DRIVER=smtp
MAIL_FROM_ADDRESS=from_address@example.com
MAIL_FROM_NAME=from_name
MAIL_HOST=localhost
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=username@example.com
MAIL_PASSWORD=password
MAIL_SENDMAIL=/usr/sbin/sendmail -bs

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($config);

        $dotenvReader = new DotenvReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $dotenvWriter = new DotenvWriter(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );

        $configMailSettingRepository = new ConfigMailSetting(
            $dotenvReader,
            $dotenvWriter
        );

        $mailSettings = $configMailSettingRepository->all();

        $this->assertEquals('smtp', $mailSettings->getDriver());
        $this->assertEquals('from_address@example.com', $mailSettings->getFrom()['address']);
        $this->assertEquals('from_name', $mailSettings->getFrom()['name']);
        $this->assertEquals('localhost', $mailSettings->getSmtpHost());
        $this->assertEquals(587, $mailSettings->getSmtpPort());
        $this->assertEquals('tls', $mailSettings->getSmtpEncryption());
        $this->assertEquals('username@example.com', $mailSettings->getSmtpUsername());
        $this->assertEquals('password', $mailSettings->getSmtpPassword());
        $this->assertEquals('/usr/sbin/sendmail -bs', $mailSettings->getSendmailPath());
    }

    public function testShouldUpdateExistingMailSettings()
    {
        vfsStream::newFile('.env')->at($this->rootDir);

        $dotenvReader = new DotenvReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $dotenvWriter = new DotenvWriter(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );

        $configMailSettingRepository = new ConfigMailSetting(
            $dotenvReader,
            $dotenvWriter
        );

        $configMailSettingRepository->update([
            'driver'          => 'smtp',
            'from_address'    => 'from_address@example.com',
            'from_name'       => 'from_name',
            'smtp_host'       => 'localhost',
            'smtp_port'       => 587,
            'smtp_encryption' => 'tls',
            'smtp_username'   => 'username@example.com',
            'smtp_password'   => 'password',
            'sendmail_path'   => '/usr/sbin/sendmail -bs',
        ]);

        $this->assertEquals('smtp', $dotenvReader->getConfig('MAIL_DRIVER'));
        $this->assertEquals('from_address@example.com', $dotenvReader->getConfig('MAIL_FROM_ADDRESS'));
        $this->assertEquals('from_name', $dotenvReader->getConfig('MAIL_FROM_NAME'));
        $this->assertEquals('localhost', $dotenvReader->getConfig('MAIL_HOST'));
        $this->assertEquals(587, $dotenvReader->getConfig('MAIL_PORT'));
        $this->assertEquals('tls', $dotenvReader->getConfig('MAIL_ENCRYPTION'));
        $this->assertEquals('username@example.com', $dotenvReader->getConfig('MAIL_USERNAME'));
        $this->assertEquals('password', $dotenvReader->getConfig('MAIL_PASSWORD'));
        $this->assertEquals('/usr/sbin/sendmail -bs', $dotenvReader->getConfig('MAIL_SENDMAIL'));
    }
}
