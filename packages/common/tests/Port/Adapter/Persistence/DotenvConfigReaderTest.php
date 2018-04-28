<?php

namespace Ngmy\Webloyer\Common\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigReader;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use TestCase;

class DotenvConfigReaderTest extends TestCase
{
    private $rootDir;

    public function setUp()
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function test_Should_GetConfigValue_When_ConfigExistsAndValueIsNotEmpty()
    {
        $contents = <<<EOF
NAME1=value1
NAME2='value2'
NAME3= v a l u e 3 
NAME4=

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contents);
        $expectedResult = 'value1';

        $dotenvConfigReader = $this->createDotenvConfigReader();

        $actualResult = $dotenvConfigReader->getConfig('NAME1');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetConfigValue_When_ConfigExistsAndValueIsSingleQuoted()
    {
        $contents = <<<EOF
NAME1=value1
NAME2='value2'
NAME3= v a l u e 3 
NAME4=

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contents);
        $expectedResult = "'value2'";

        $dotenvConfigReader = $this->createDotenvConfigReader();

        $actualResult = $dotenvConfigReader->getConfig('NAME2');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetConfigValue_When_ConfigExistsAndValueHasWhitespaces()
    {
        $contents = <<<EOF
NAME1=value1
NAME2='value2'
NAME3= v a l u e 3 
NAME4=

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contents);
        $expectedResult = ' v a l u e 3 ';

        $dotenvConfigReader = $this->createDotenvConfigReader();

        $actualResult = $dotenvConfigReader->getConfig('NAME3');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetNull_When_ConfigExistsAndValueIsEmpty()
    {
        $contents = <<<EOF
NAME1=value1
NAME2='value2'
NAME3= v a l u e 3 
NAME4=

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contents);

        $dotenvConfigReader = $this->createDotenvConfigReader();

        $actualResult = $dotenvConfigReader->getConfig('NAME4');

        $this->assertNull($actualResult);
    }

    public function test_Should_GetNull_When_ConfigDoesNotExist()
    {
        $contents = <<<EOF
NAME1=value1
NAME2='value2'
NAME3= v a l u e 3 
NAME4=

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contents);

        $dotenvConfigReader = $this->createDotenvConfigReader();

        $actualResult = $dotenvConfigReader->getConfig('NAME5');

        $this->assertNull($actualResult);
    }

    private function createDotenvConfigReader(array $params = [])
    {
        extract($params);

        return new DotenvConfigReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
    }
}
