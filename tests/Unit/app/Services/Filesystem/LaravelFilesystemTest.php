<?php

namespace Tests\Unit\app\Services\Filesystem;

use App\Services\Filesystem\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use Tests\TestCase;

class LaravelFilesystemTest extends TestCase
{
    protected $rootDir;

    public function setUp(): void
    {
        parent::setUp();

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function testShouldWriteFile()
    {
        $fs = new LaravelFilesystem($this->app['files']);
        $fs->put(vfsStream::url('rootDir/file'), 'contents');

        $putFile = $this->rootDir->getChild('file')->getContent();

        $this->assertEquals('contents', $putFile);
    }

    public function testShouldReadFile()
    {
        $file = vfsStream::newFile('file')->at($this->rootDir)->setContent('contents');

        $fs = new LaravelFilesystem($this->app['files']);
        $getFile = $fs->get(vfsStream::url('rootDir/file'));

        $this->assertEquals('contents', $getFile);
    }

    public function testShouldDeleteFile()
    {
        $file = vfsStream::newFile('file')->at($this->rootDir);

        $fs = new LaravelFilesystem($this->app['files']);
        $fs->delete(vfsStream::url('rootDir/file'));

        $this->assertFileNotExists(vfsStream::url('rootDir/file'));
    }
}
