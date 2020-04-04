<?php

namespace Tests\Unit\app\Services\Config;

use App\Services\Config\DotenvReader;
use App\Services\Filesystem\LaravelFilesystem;

use org\bovigo\vfs\vfsStream;
use Tests\TestCase;

class DotenvReaderTest extends TestCase
{
    protected $rootDir;

    public function setUp(): void
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

        $dotenvReader = new DotenvReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $value = $dotenvReader->getConfig('NAME1');

        $this->assertEquals('value1', $value);
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

        $dotenvReader = new DotenvReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $value = $dotenvReader->getConfig('NAME2');

        $this->assertEquals("'value2'", $value);
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

        $dotenvReader = new DotenvReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $value = $dotenvReader->getConfig('NAME3');

        $this->assertEquals(' v a l u e 3 ', $value);
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

        $dotenvReader = new DotenvReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $value = $dotenvReader->getConfig('NAME4');

        $this->assertNull($value);
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

        $dotenvReader = new DotenvReader(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $value = $dotenvReader->getConfig('NAME5');

        $this->assertNull($value);
    }
}
