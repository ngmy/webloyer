<?php

namespace Tests\Unit\app\Repositories\Setting;

use App\Repositories\Setting\ConfigAppSetting;
use App\Services\Config\DotenvReader;
use App\Services\Config\DotenvWriter;
use App\Services\Filesystem\LaravelFilesystem;

use org\bovigo\vfs\vfsStream;
use Tests\TestCase;

class ConfigAppSettingTest extends TestCase
{
    protected $rootDir;

    public function setUp(): void
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function test_Should_GetAllAppSettings()
    {
        $config = <<<EOF
APP_URL=http://example.com

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

        $configAppSettingRepository = new ConfigAppSetting(
            $dotenvReader,
            $dotenvWriter
        );

        $appSettings = $configAppSettingRepository->all();

        $this->assertEquals('http://example.com', $appSettings->getUrl());
    }

    public function test_Should_UpdateExistingAppSettings()
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

        $configAppSettingRepository = new ConfigAppSetting(
            $dotenvReader,
            $dotenvWriter
        );

        $configAppSettingRepository->update([
            'url' => 'http://example.com',
        ]);

        $this->assertEquals('http://example.com', $dotenvReader->getConfig('APP_URL'));
    }
}
