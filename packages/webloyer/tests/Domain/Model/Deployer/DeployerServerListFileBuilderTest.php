<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use org\bovigo\vfs\vfsStream;
use Ngmy\Webloyer\Common\Filesystem\FilesystemInterface;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerServerListFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Symfony\Component\Yaml\Dumper as YamlDumper;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;
use TestCase;
use Tests\Helpers\MockeryHelper;

class DeployerServerListFileBuilderTest extends TestCase
{
    use MockeryHelper;

    private $fs;

    private $deployerFile;

    private $yamlParser;

    private $yamlDumper;

    private $rootDir;

    private $project;

    private $projectAttribute;

    private $server;

    public function setUp()
    {
        parent::setUp();

        $this->fs = $this->partialMock(new LaravelFilesystem($this->app['files']));
        $this->deployerFile = $this->partialMock(DeployerFile::class);
        $this->yamlParser = $this->partialMock(YamlParser::class);
        $this->yamlDumper = $this->partialMock(YamlDumper::class);

        $this->rootDir = vfsStream::setup('rootDir');

        $this->project = $this->partialMock(Project::class);
        $this->projectAttribute = $this->mock(new ProjectAttribute('deploy_path'));
        $this->server = $this->partialMock(Server::class);
    }

    public function test_Should_BuildDeployerServerListFile_When_ProjectAttributeIsNotSpecified()
    {
        $expectedBaseNamePattern = '|server_[a-zA-Z0-9]{32}.yml|';
        $expectedFullPathPattern = '|'. storage_path('app/server_[a-zA-Z0-9]{32}.yml') . '|';

        $serverBody = '';
        $serverList = [];
        $newServerBody = '';

        $this->server
            ->shouldReceive('body')
            ->withNoArgs()
            ->andReturn($serverBody)
            ->once();

        $this->project
            ->shouldReceive('attribute')
            ->withNoArgs()
            ->andReturn(null)
            ->once();

        $this->fs
            ->shouldReceive('delete')
            ->with($expectedFullPathPattern)
            ->once();
        $this->fs
            ->shouldReceive('put')
            ->with($expectedFullPathPattern, $newServerBody)
            ->once();

        $this->yamlParser
            ->shouldReceive('parse')
            ->with($serverBody)
            ->andReturn($serverList)
            ->once();

        $this->yamlDumper
            ->shouldReceive('dump')
            ->with($serverList)
            ->andReturn($newServerBody)
            ->once();

        $deployerServerListFileBuilder = new DeployerServerListFileBuilder(
            $this->fs,
            $this->deployerFile,
            $this->yamlParser,
            $this->yamlDumper
        );
        $actualResult = $deployerServerListFileBuilder
            ->setServer($this->server)
            ->setProject($this->project)
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertRegExp($expectedBaseNamePattern, $actualResult->getBaseName());
        $this->assertRegExp($expectedFullPathPattern, $actualResult->getFullPath());
    }

    public function test_Should_OverrideAttributeInDeployerServerListFile_When_ProjectAttributeIsSpecified()
    {
        $expectedBaseNamePattern = '|server_[a-zA-Z0-9]{32}.yml|';
        $expectedFullPathPattern = '|'. storage_path('app/server_[a-zA-Z0-9]{32}.yml') . '|';
        $expectedServerListFileDeployPath = '/home/www/deploy2';

        $fullPath = vfsStream::url('rootDir/server.yml');
        $serverBody = <<<EOF
test:
  host: localhost
  user: www
  identity_file:
    public_key: /path/to/public_key
    private_key: /path/to/private_key
  stage: testing
  deploy_path: /home/www/deploy1
EOF;

        $this->server
            ->shouldReceive('body')
            ->withNoArgs()
            ->andReturn($serverBody)
            ->once();

        $this->projectAttribute
            ->shouldReceive('deployPath')
            ->withNoArgs()
            ->andReturn($expectedServerListFileDeployPath)
            ->twice();

        $this->project
            ->shouldReceive('attribute')
            ->withNoArgs()
            ->andReturn($this->projectAttribute)
            ->once();

        $this->deployerFile
            ->shouldReceive('setBaseName')
            ->with($expectedBaseNamePattern)
            ->once();
        $this->deployerFile
            ->shouldReceive('setFullPath')
            ->with($expectedFullPathPattern)
            ->once();
        $this->deployerFile
            ->shouldReceive('getFullPath')
            ->withNoArgs()
            ->andReturn($fullPath)
            ->twice();

        $deployerServerListFileBuilder = new DeployerServerListFileBuilder(
            $this->fs,
            $this->deployerFile,
            $this->yamlParser,
            $this->yamlDumper
        );
        $deployerServerListFileBuilder
            ->setServer($this->server)
            ->setProject($this->project)
            ->pathInfo()
            ->put()
            ->getResult();

        $actualResult = Yaml::parse(file_get_contents($fullPath));

        $expectedResult = Yaml::parse($serverBody);
        $expectedResult['test']['deploy_path'] = $expectedServerListFileDeployPath;

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_AppendAttributeToDeployerServerListFile_When_ProjectAttributeIsSpecified()
    {
        $expectedBaseNamePattern = '|server_[a-zA-Z0-9]{32}.yml|';
        $expectedFullPathPattern = '|'. storage_path('app/server_[a-zA-Z0-9]{32}.yml') . '|';
        $expectedServerListFileDeployPath = '/home/www/deploy2';

        $fullPath = vfsStream::url('rootDir/server.yml');
        $serverBody = <<<EOF
test:
  host: localhost
  user: www
  identity_file:
    public_key: /path/to/public_key
    private_key: /path/to/private_key
  stage: testing
EOF;

        $this->server
            ->shouldReceive('body')
            ->withNoArgs()
            ->andReturn($serverBody)
            ->once();

        $this->projectAttribute
            ->shouldReceive('deployPath')
            ->withNoArgs()
            ->andReturn($expectedServerListFileDeployPath)
            ->twice();

        $this->project
            ->shouldReceive('attribute')
            ->withNoArgs()
            ->andReturn($this->projectAttribute)
            ->once();

        $this->deployerFile
            ->shouldReceive('setBaseName')
            ->with($expectedBaseNamePattern)
            ->once();
        $this->deployerFile
            ->shouldReceive('setFullPath')
            ->with($expectedFullPathPattern)
            ->once();
        $this->deployerFile
            ->shouldReceive('getFullPath')
            ->withNoArgs()
            ->andReturn($fullPath)
            ->twice();

        $deployerServerListFileBuilder = new DeployerServerListFileBuilder(
            $this->fs,
            $this->deployerFile,
            $this->yamlParser,
            $this->yamlDumper
        );
        $deployerServerListFileBuilder
            ->setServer($this->server)
            ->setProject($this->project)
            ->pathInfo()
            ->put()
            ->getResult();

        $actualResult = Yaml::parse(file_get_contents($fullPath));

        $expectedResult = Yaml::parse($serverBody);
        $expectedResult['test']['deploy_path'] = $expectedServerListFileDeployPath;

        $this->assertEquals($expectedResult, $actualResult);
    }
}
