<?php

namespace Tests\Unit\app\Repositories\Setting;

use App\Repositories\Setting\ConfigDbSetting;
use App\Services\Config\DotenvReader;
use App\Services\Config\DotenvWriter;
use App\Services\Filesystem\LaravelFilesystem;

use org\bovigo\vfs\vfsStream;

class ConfigDbSettingTest extends TestCase
{
    protected $rootDir;

    public function setUp(): void
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function test_Should_GetAllDbSettings()
    {
        $config = <<<EOF
DB_DRIVER=mysql
DB_HOST=localhost
DB_DATABASE=database
DB_USERNAME=username
DB_PASSWORD=password

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

        $configDbSettingRepository = new ConfigDbSetting(
            $dotenvReader,
            $dotenvWriter
        );

        $dbSettings = $configDbSettingRepository->all();

        $this->assertEquals('mysql',     $dbSettings->getDriver());
        $this->assertEquals('localhost', $dbSettings->getHost());
        $this->assertEquals('database',  $dbSettings->getDatabase());
        $this->assertEquals('username',  $dbSettings->getUsername());
        $this->assertEquals('password',  $dbSettings->getPassword());
    }

    public function test_Should_UpdateExistingDbSettings()
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

        $configDbSettingRepository = new ConfigDbSetting(
            $dotenvReader,
            $dotenvWriter
        );

        $configDbSettingRepository->update([
            'driver'   => 'mysql',
            'host'     => 'localhost',
            'database' => 'database',
            'username' => 'username',
            'password' => 'password',
        ]);

        $this->assertEquals('mysql',     $dotenvReader->getConfig('DB_DRIVER'));
        $this->assertEquals('localhost', $dotenvReader->getConfig('DB_HOST'));
        $this->assertEquals('database',  $dotenvReader->getConfig('DB_DATABASE'));
        $this->assertEquals('username',  $dotenvReader->getConfig('DB_USERNAME'));
        $this->assertEquals('password',  $dotenvReader->getConfig('DB_PASSWORD'));
    }
}
