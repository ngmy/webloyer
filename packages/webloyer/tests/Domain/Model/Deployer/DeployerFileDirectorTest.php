<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileDirector;
use TestCase;
use Tests\Helpers\MockeryHelper;

class DeployerFileDirectorTest extends TestCase
{
    use MockeryHelper;

    private $deployerFileDirector;

    private $fileBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->fileBuilder = $this->mock(DeployerFileBuilderInterface::class);
        $this->deployerFileDirector = new DeployerFileDirector($this->fileBuilder);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_ConstructDeployerFileInstance()
    {
        $expectedResult = $this->mock(DeployerFile::class);

        $this->fileBuilder
            ->shouldReceive('pathInfo')
            ->withNoArgs()
            ->once();
        $this->fileBuilder
            ->shouldReceive('put')
            ->withNoArgs()
            ->once();
        $this->fileBuilder
            ->shouldReceive('getResult')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->deployerFileDirector->construct();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
