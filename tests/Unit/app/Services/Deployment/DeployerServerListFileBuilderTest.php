<?php

namespace Tests\Unit\app\Services\Deployment;

use App\Entities\ProjectAttribute\ProjectAttributeEntity;
use App\Models\Project;
use App\Models\Server;
use App\Services\Deployment\DeployerServerListFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\FilesystemInterface;
use App\Services\Filesystem\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class DeployerServerListFileBuilderTest extends TestCase
{
    protected $mockProjectModel;

    protected $mockProjectAttributeEntity;

    protected $mockServerModel;

    protected $mockFilesystem;

    protected $mockYamlParser;

    protected $mockYamlDumper;

    protected $mockDeployerFile;

    protected $rootDir;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockProjectModel = $this->partialMock(Project::class);
        $this->mockProjectAttributeEntity = $this->mock(ProjectAttributeEntity::class);
        $this->mockServerModel = $this->partialMock(Server::class);
        $this->mockFilesystem = $this->mock(FilesystemInterface::class);
        $this->mockYamlParser = $this->mock(Parser::class);
        $this->mockYamlDumper = $this->mock(Dumper::class);
        $this->mockDeployerFile = $this->mock(DeployerFile::class);

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function testShouldBuildDeployerServerListFile()
    {
        $this->mockProjectModel
            ->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockProjectAttributeEntity);

        $this->mockFilesystem
            ->shouldReceive('delete')
            ->once();
        $this->mockFilesystem
            ->shouldReceive('put')
            ->once();

        $this->mockYamlParser
            ->shouldReceive('parse')
            ->once()
            ->andReturn([]);

        $this->mockYamlDumper
            ->shouldReceive('dump')
            ->once()
            ->andReturn('');

        $serverListFileBuilder = new DeployerServerListFileBuilder(
            $this->mockFilesystem,
            new DeployerFile(),
            $this->mockYamlParser,
            $this->mockYamlDumper
        );
        $this->mockServerModel->body = '';
        $serverListFileBuilder->setServer($this->mockServerModel)
            ->setProject($this->mockProjectModel);
        $result = $serverListFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertStringMatchesFormat('server_%x.yml', $result->getBaseName());
        $this->assertStringMatchesFormat(storage_path("app/server_%x.yml"), $result->getFullPath());
    }

    public function testShouldOverrideAttributeInDeployerServerListFileWhenProjectAttributeIsSpecified()
    {
        $path = vfsStream::url('rootDir/server.yml');

        $this->mockProjectAttributeEntity
            ->shouldReceive('getDeployPath')
            ->twice()
            ->andReturn('/home/www/deploy2');

        $this->mockProjectModel
            ->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockProjectAttributeEntity);

        $this->mockDeployerFile
            ->shouldReceive('setBaseName')
            ->once();
        $this->mockDeployerFile
            ->shouldReceive('setFullPath')
            ->once();
        $this->mockDeployerFile
            ->shouldReceive('getFullPath')
            ->andReturn($path);

        $serverListFileBuilder = new DeployerServerListFileBuilder(
            new LaravelFilesystem($this->app['files']),
            $this->mockDeployerFile,
            new Parser(),
            new Dumper()
        );

        $this->mockServerModel->body = <<<EOF
test:
  host: localhost
  user: www
  identity_file:
    public_key: /path/to/public_key
    private_key: /path/to/private_key
  stage: testing
  deploy_path: /home/www/deploy1
EOF;

        $serverListFileBuilder->setServer($this->mockServerModel)
            ->setProject($this->mockProjectModel);
        $result = $serverListFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $serverListFile = Yaml::parse(file_get_contents($path));

        $this->assertEquals('/home/www/deploy2', $serverListFile['test']['deploy_path']);
    }

    public function testShouldOverrideAttributeInDeployerServerListFileWhenProjectAttributeIsNotSpecified()
    {
        $path = vfsStream::url('rootDir/server.yml');

        $this->mockProjectAttributeEntity
            ->shouldReceive('getDeployPath')
            ->twice()
            ->andReturn('/home/www/deploy2');

        $this->mockProjectModel
            ->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockProjectAttributeEntity);

        $this->mockDeployerFile
            ->shouldReceive('setBaseName')
            ->once();
        $this->mockDeployerFile
            ->shouldReceive('setFullPath')
            ->once();
        $this->mockDeployerFile
            ->shouldReceive('getFullPath')
            ->andReturn($path);

        $serverListFileBuilder = new DeployerServerListFileBuilder(
            new LaravelFilesystem($this->app['files']),
            $this->mockDeployerFile,
            new Parser(),
            new Dumper()
        );

        $this->mockServerModel->body = <<<EOF
test:
  host: localhost
  user: www
  identity_file:
    public_key: /path/to/public_key
    private_key: /path/to/private_key
  stage: testing
EOF;

        $serverListFileBuilder->setServer($this->mockServerModel)
            ->setProject($this->mockProjectModel);
        $result = $serverListFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $serverListFile = Yaml::parse(file_get_contents($path));

        $this->assertEquals('/home/www/deploy2', $serverListFile['test']['deploy_path']);
    }
}
