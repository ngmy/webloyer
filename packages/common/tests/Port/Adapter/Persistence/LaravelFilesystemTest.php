<?php

namespace Ngmy\Webloyer\Common\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use TestCase;

class LaravelFilesystemTest extends TestCase
{
    private $rootDir;

    public function setUp()
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function test_Should_WriteFile()
    {
        $expectedResult = 'contents';
        $laravelFilesystem = $this->createLaravelFilesystem();

        $laravelFilesystem->put(vfsStream::url('rootDir/file'), $expectedResult);

        $actualResult = $this->rootDir->getChild('file')->getContent();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_ReadFile()
    {
        $expectedResult = 'contents';
        $file = vfsStream::newFile('file')->at($this->rootDir)->setContent($expectedResult);

        $laravelFilesystem = $this->createLaravelFilesystem();

        $actualResult = $laravelFilesystem->get(vfsStream::url('rootDir/file'));

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_DeleteFile()
    {
        vfsStream::newFile('file')->at($this->rootDir);

        $laravelFilesystem = $this->createLaravelFilesystem();

        $laravelFilesystem->delete(vfsStream::url('rootDir/file'));

        $this->assertFileNotExists(vfsStream::url('rootDir/file'));
    }

    private function createLaravelFilesystem(array $params = [])
    {
        extract($params);

        return new LaravelFilesystem($this->app['files']);
    }
}
