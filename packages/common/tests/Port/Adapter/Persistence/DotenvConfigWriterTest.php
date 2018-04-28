<?php

namespace Ngmy\Webloyer\Common\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigWriter;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use TestCase;

class DotenvConfigWriterTest extends TestCase
{
    private $rootDir;

    public function setUp()
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function test_Should_UpdateExistingConfig_When_ConfigExists()
    {
        $contents = <<<EOF
NAME1=value1
NAME2=value2

EOF;

        $expectedResult = <<<EOF
NAME1=value1a
NAME2=value2

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contents);

        $dotenvConfigWriter = $this->createDotenvConfigWriter();
        $dotenvConfigWriter->setConfig('NAME1', 'value1a');

        $actualResult = $dotenv->getContent();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_AddNewConfig_When_ConfigDoesNotExist()
    {
        $contents = <<<EOF
NAME1=value1
NAME2=value2

EOF;

        $expectedResult = <<<EOF
NAME1=value1
NAME2=value2
NAME3=value3

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contents);

        $dotenvConfigWriter = $this->createDotenvConfigWriter();
        $dotenvConfigWriter->setConfig('NAME3', 'value3');

        $actualResult = $dotenv->getContent();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDotenvConfigWriter(array $params = [])
    {
        extract($params);

        return new DotenvConfigWriter(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
    }
}
