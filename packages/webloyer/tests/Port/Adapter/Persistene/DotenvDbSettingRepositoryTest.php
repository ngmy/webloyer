<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigReader;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigWriter;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\DotenvDbSettingRepository;
use org\bovigo\vfs\vfsStream;
use TestCase;

class DotenvDbSettingRepositoryTest extends TestCase
{
    private $rootDir;

    public function setUp()
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function dbSettingProvider()
    {
        return [
            'DbSettingInDotenvIsNotEmpty' => [
                'DB_DRIVER=mysql'      . PHP_EOL .
                'DB_HOST=mysql'        . PHP_EOL .
                'DB_DATABASE=webloyer' . PHP_EOL .
                'DB_USERNAME=root'     . PHP_EOL .
                'DB_PASSWORD=root'     . PHP_EOL,
                $this->createDbSetting([
                    'driver'   => 'mysql',
                    'host'     => 'mysql',
                    'database' => 'webloyer',
                    'userName' => 'root',
                    'password' => 'root',
                ]),
            ],
            'DbSettingInDotenvIsEmpty' => [
                'DB_DRIVER=mysql' . PHP_EOL .
                'DB_HOST='        . PHP_EOL .
                'DB_DATABASE='    . PHP_EOL .
                'DB_USERNAME='    . PHP_EOL .
                'DB_PASSWORD='    . PHP_EOL,
                $this->createDbSetting(),
            ],
            'DbSettingInDotenvNotExists' => [
                'DB_DRIVER=mysql' . PHP_EOL,
                $this->createDbSetting(),
            ],
        ];
    }

    public function saveProvider()
    {
        return [
            'DbSettingInDotenvIsNotEmpty' => [
                'DB_DRIVER=mysql'      . PHP_EOL .
                'DB_HOST=mysql'        . PHP_EOL .
                'DB_DATABASE=webloyer' . PHP_EOL .
                'DB_USERNAME=root'     . PHP_EOL .
                'DB_PASSWORD=root'     . PHP_EOL,
                $this->createDbSetting([
                    'driver'   => 'pgsql',
                    'host'     => 'pgsql',
                    'database' => 'webloyer2',
                    'userName' => 'admin',
                    'password' => 'admin',
                ]),
                'DB_DRIVER=pgsql'       . PHP_EOL .
                'DB_HOST=pgsql'         . PHP_EOL .
                'DB_DATABASE=webloyer2' . PHP_EOL .
                'DB_USERNAME=admin'     . PHP_EOL .
                'DB_PASSWORD=admin'     . PHP_EOL,
            ],
            'DbSettingInDotenvIsEmpty' => [
                'DB_DRIVER=mysql' . PHP_EOL .
                'DB_HOST='        . PHP_EOL .
                'DB_DATABASE='    . PHP_EOL .
                'DB_USERNAME='    . PHP_EOL .
                'DB_PASSWORD='    . PHP_EOL,
                $this->createDbSetting([
                    'driver'   => 'pgsql',
                    'host'     => 'pgsql',
                    'database' => 'webloyer2',
                    'userName' => 'admin',
                    'password' => 'admin',
                ]),
                'DB_DRIVER=pgsql'       . PHP_EOL .
                'DB_HOST=pgsql'         . PHP_EOL .
                'DB_DATABASE=webloyer2' . PHP_EOL .
                'DB_USERNAME=admin'     . PHP_EOL .
                'DB_PASSWORD=admin'     . PHP_EOL,
            ],
            'DbSettingInDotenvNotExists' => [
                'DB_DRIVER=mysql' . PHP_EOL,
                $this->createDbSetting([
                    'driver'   => 'pgsql',
                    'host'     => 'pgsql',
                    'database' => 'webloyer2',
                    'userName' => 'admin',
                    'password' => 'admin',
                ]),
                'DB_DRIVER=pgsql'       . PHP_EOL .
                'DB_HOST=pgsql'         . PHP_EOL .
                'DB_DATABASE=webloyer2' . PHP_EOL .
                'DB_USERNAME=admin'     . PHP_EOL .
                'DB_PASSWORD=admin'     . PHP_EOL,
            ],
        ];
    }

    /**
     * @dataProvider dbSettingProvider
     */
    public function test_Should_GetDbSetting_When_($dotenvContents, $expectedResult)
    {
        vfsStream::newFile('.env')->at($this->rootDir)->setContent($dotenvContents);

        $actualResult = $this->createDotenvDbSettingRepository()->dbSetting();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider saveProvider
     */
    public function test_Should_SaveDbSettng_When_($dotenvContents, $newDbSetting, $expectedResult)
    {
        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($dotenvContents);

        $this->createDotenvDbSettingRepository()->save($newDbSetting);

        $actualResult = $dotenv->getContent();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDbSetting(array $params = [])
    {
        $driver = 'mysql';
        $host = '';
        $database = '';
        $userName = '';
        $password = '';

        extract($params);

        return new DbSetting(
            new DbSettingDriver($driver),
            $host,
            $database,
            $userName,
            $password
        );
    }

    private function createDotenvDbSettingRepository(array $params = [])
    {
        extract($params);

        return new DotenvDbSettingRepository(
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
