<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployment;

use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentPresenter;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Tests\Helpers\MockeryHelper;
use TestCase;

class DeploymentPresenterTest extends TestCase
{
    use MockeryHelper;

    private $deploymentPresenter;

    private $deployment;

    private $converter;

    public function setUp()
    {
        parent::setUp();

        $this->deployment = $this->mock(Deployment::class);
        $this->converter = $this->mock(AnsiToHtmlConverter::class);
        $this->deploymentPresenter = new DeploymentPresenter(
            $this->deployment,
            $this->converter
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_ConvertStatusToIcon_When_StatusIsSuccess()
    {
        $expectedResult = '<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>';

        $this->deployment
            ->shouldReceive('status->isSuccess')
            ->withNoArgs()
            ->andReturn(true)
            ->once();

        $actualResult = $this->deploymentPresenter->statusIcon();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_ConvertStatusToIcon_When_StatusIsFailure()
    {
        $expectedResult = '<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>';

        $this->deployment
            ->shouldReceive('status->isSuccess')
            ->withNoArgs()
            ->andReturn(false)
            ->once();
        $this->deployment
            ->shouldReceive('status->isFailure')
            ->withNoArgs()
            ->andReturn(true)
            ->once();

        $actualResult = $this->deploymentPresenter->statusIcon();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_ConvertStatusToIcon_When_StatusIsRunning()
    {
        $expectedResult = '<span></span>';

        $this->deployment
            ->shouldReceive('status->isSuccess')
            ->withNoArgs()
            ->andReturn(false)
            ->once();
        $this->deployment
            ->shouldReceive('status->isFailure')
            ->withNoArgs()
            ->andReturn(false)
            ->once();

        $actualResult = $this->deploymentPresenter->statusIcon();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_ConvertMessageToHtml()
    {
        $expectedResult = '<html></html>';
        $message = '';

        $this->deployment
            ->shouldReceive('message')
            ->withNoArgs()
            ->andReturn($message)
            ->once();
        $this->converter
            ->shouldReceive('convert')
            ->with($message)
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->deploymentPresenter->messageHtml();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_ConvertMessageToText()
    {
        $expectedResult = '';
        $message = '';
        $html = '<html></html>';

        $this->deployment
            ->shouldReceive('message')
            ->withNoArgs()
            ->andReturn($message)
            ->once();
        $this->converter
            ->shouldReceive('convert')
            ->with($message)
            ->andReturn($html)
            ->once();

        $actualResult = $this->deploymentPresenter->messageText();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
