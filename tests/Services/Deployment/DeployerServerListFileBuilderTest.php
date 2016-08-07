<?php

use App\Services\Deployment\DeployerServerListFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\LaravelFilesystem;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class DeployerServerListFileBuilderTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockProjectModel;

    protected $mockProjectAttributeEntity;

    protected $mockServerModel;

    protected $mockFilesystem;

    protected $mockYamlParser;

    protected $mockYamlDumper;

    protected $mockDeployerFile;

    protected $rootDir;

    public function setUp()
    {
        parent::setUp();

        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
        $this->mockProjectAttributeEntity = $this->mock('App\Entities\ProjectAttribute\ProjectAttributeEntity');
        $this->mockServerModel = $this->mockPartial('App\Models\Server');
        $this->mockFilesystem = $this->mock('App\Services\Filesystem\FilesystemInterface');
        $this->mockYamlParser = $this->mock('Symfony\Component\Yaml\Parser');
        $this->mockYamlDumper = $this->mock('Symfony\Component\Yaml\Dumper');
        $this->mockDeployerFile = $this->mock('App\Services\Deployment\DeployerFile');

        $this->rootDir = vfsStream::setup('rootDir');
    }

    public function test_Should_BuildDeployerServerListFile()
    {
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
            new DeployerFile,
            $this->mockYamlParser,
            $this->mockYamlDumper
        );
        $serverListFileBuilder->setServer($this->mockServerModel)
            ->setProject($this->mockProjectModel);
        $result = $serverListFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertStringMatchesFormat('server_%x.yml', $result->getBaseName());
        $this->assertStringMatchesFormat(storage_path("app/server_%x.yml"), $result->getFullPath());
    }

    public function test_Should_OverrideAttributeInDeployerServerListFile_When_ProjectAttributeIsSpecified()
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
            new Parser,
            new Dumper
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

    public function test_Should_OverrideAttributeInDeployerServerListFile_When_ProjectAttributeIsNotSpecified()
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
            new Parser,
            new Dumper
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
