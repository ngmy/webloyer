<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use TestCase;
use Tests\Helpers\MockeryHelper;

class DeployerDeploymentFileBuilderTest extends TestCase
{
    use MockeryHelper;

    private $fs;

    private $deployerFile;

    private $project;

    private $serverListFile;

    private $recipeFile;

    public function setUp()
    {
        parent::setUp();

        $this->fs = $this->partialMock(new LaravelFilesystem($this->app['files']));
        $this->deployerFile = $this->partialMock(DeployerFile::class);

        $this->project = $this->mock(Project::class);
        $this->serverListFile = $this->mock(DeployerFile::class);
        $this->recipeFile = $this->mock(DeployerFile::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_BuildDeployerDeploymentFile()
    {
        $expectedDeploymentFileBaseNamePattern = '|deploy_[a-zA-Z0-9]{32}.php|';
        $expectedDeploymentFileFullPathPattern = '|'. storage_path('app/deploy_[a-zA-Z0-9]{32}.php') . '|';

        $repositoryUrl = 'http://example.com';
        $serverListFileFullPath = 'server.php';
        $recipeFileFullPath = 'recipe.php';
        $expectedDeploymentFileContents = implode(PHP_EOL, [
            '<?php',
            'namespace Deployer;',
            "require '$recipeFileFullPath';",
            "set('repository', '$repositoryUrl');",
            "serverList('$serverListFileFullPath');",
        ]);

        $this->fs
            ->shouldReceive('delete')
            ->with($expectedDeploymentFileFullPathPattern)
            ->once();
        $this->fs
            ->shouldReceive('put')
            ->with($expectedDeploymentFileFullPathPattern, $expectedDeploymentFileContents)
            ->once();

        $this->project
            ->shouldReceive('repositoryUrl')
            ->withNoArgs()
            ->andReturn($repositoryUrl)
            ->once();

        $this->serverListFile
            ->shouldReceive('getFullPath')
            ->withNoArgs()
            ->andReturn($serverListFileFullPath)
            ->once();

        $this->recipeFile
            ->shouldReceive('getFullPath')
            ->withNoArgs()
            ->andReturn($recipeFileFullPath)
            ->once();

        $deployerDeploymentFileBuilder = new DeployerDeploymentFileBuilder(
            $this->fs,
            $this->deployerFile
        );
        $actualResult = $deployerDeploymentFileBuilder
            ->setProject($this->project)
            ->setServerListFile($this->serverListFile)
            ->setRecipeFile([$this->recipeFile])
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertRegExp($expectedDeploymentFileBaseNamePattern, $actualResult->getBaseName());
        $this->assertRegExp($expectedDeploymentFileFullPathPattern, $actualResult->getFullPath());
    }
}
