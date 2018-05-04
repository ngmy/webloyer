<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigReader;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigWriter;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSetting;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\DotenvAppSettingRepository;
use org\bovigo\vfs\vfsStream;
use TestCase;

class DotenvAppSettingRepositoryTest extends TestCase
{
    private $rootDir;

    public function setUp()
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function appSettingProvider()
    {
        return [
            'AppSettingInDotenvIsNotEmpty' => [
                'APP_URL=http://example.com' . PHP_EOL,
                $this->createAppSetting([
                    'url' => 'http://example.com',
                ]),
            ],
            'AppSettingInDotenvIsEmpty' => [
                'APP_URL=' . PHP_EOL,
                $this->createAppSetting(),
            ],
            'AppSettingInDotenvNotExists' => [
                '',
                $this->createAppSetting(),
            ],
        ];
    }

    public function saveProvider()
    {
        return [
            'AppSettingInDotenvIsNotEmpty' => [
                'APP_URL=http://example.com' . PHP_EOL,
                $this->createAppSetting([
                    'url' => 'http://example.net',
                ]),
                'APP_URL=http://example.net' . PHP_EOL,
            ],
            'AppSettingInDotenvIsEmpty' => [
                'APP_URL=' . PHP_EOL,
                $this->createAppSetting([
                    'url' => 'http://example.net',
                ]),
                'APP_URL=http://example.net' . PHP_EOL,
            ],
            'AppSettingInDotenvNotExists' => [
                '',
                $this->createAppSetting([
                    'url' => 'http://example.net',
                ]),
                'APP_URL=http://example.net' . PHP_EOL,
            ],
        ];
    }

    /**
     * @dataProvider appSettingProvider
     */
    public function test_Should_GetAppSetting_When_($dotenvContents, $expectedResult)
    {
        vfsStream::newFile('.env')->at($this->rootDir)->setContent($dotenvContents);

        $actualResult = $this->createDotenvAppSettingRepository()->appSetting();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider saveProvider
     */
    public function test_Should_SaveAppSettng_When_($dotenvContents, $newAppSetting, $expectedResult)
    {
        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($dotenvContents);

        $this->createDotenvAppSettingRepository()->save($newAppSetting);

        $actualResult = $dotenv->getContent();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createAppSetting(array $params = [])
    {
        $url = '';

        extract($params);

        return new AppSetting(
            $url
        );
    }

    private function createDotenvAppSettingRepository(array $params = [])
    {
        extract($params);

        return new DotenvAppSettingRepository(
            new DotenvConfigReader(
                new LaravelFilesystem($this->app['files']),
                vfsStream::url('rootDir/.env')
            ),
            new DotenvConfigWriter(
                new LaravelFilesystem($this->app['files']),
                vfsStream::url('rootDir/.env')
            )
        );
    }
}
