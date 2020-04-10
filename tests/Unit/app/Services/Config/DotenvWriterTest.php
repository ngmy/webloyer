<?php

namespace Tests\Unit\app\Services\Config;

use App\Services\Config\DotenvWriter;
use App\Services\Filesystem\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use Tests\TestCase;

class DotenvWriterTest extends TestCase
{
    protected $rootDir;

    public function setUp(): void
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function test_Should_UpdateExistingConfig_When_ConfigExists()
    {
        $contentsBefore = <<<EOF
NAME1=value1
NAME2=value2

EOF;

        $contentsAfter = <<<EOF
NAME1=value1a
NAME2=value2

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contentsBefore);

        $dotenvWriter = new DotenvWriter(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $dotenvWriter->setConfig('NAME1', 'value1a');

        $this->assertEquals($contentsAfter, $dotenv->getContent());
    }

    public function test_Should_AddNewConfig_When_ConfigDoesNotExist()
    {
        $contentsBefore = <<<EOF
NAME1=value1
NAME2=value2

EOF;

        $contentsAfter = <<<EOF
NAME1=value1
NAME2=value2
NAME3=value3

EOF;

        $dotenv = vfsStream::newFile('.env')->at($this->rootDir)->setContent($contentsBefore);

        $dotenvWriter = new DotenvWriter(
            new LaravelFilesystem($this->app['files']),
            vfsStream::url('rootDir/.env')
        );
        $dotenvWriter->setConfig('NAME3', 'value3');

        $this->assertEquals($contentsAfter, $dotenv->getContent());
    }
}
