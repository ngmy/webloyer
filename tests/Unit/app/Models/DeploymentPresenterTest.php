<?php

namespace Tests\Unit\app\Models;

use App\Models\Deployment;
use App\Models\DeploymentPresenter;
use App\Models\User;
use Carbon\Carbon;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Tests\TestCase;

class DeploymentPresenterTest extends TestCase
{
    public function test_Should_ConvertStatusToHtmlSnippet_When_StatusIsOK()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 0,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->status();

        $this->assertEquals('<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>', $html);
    }

    public function test_Should_ConvertStatusToHtmlSnippet_When_StatusIsNg()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 1,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->status();

        $this->assertEquals('<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>', $html);
    }

    public function test_Should_ConvertStatusToHtmlSnippet_When_StatusIsUnknown()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => null,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->status();

        $this->assertEquals('<span></span>', $html);
    }

    public function test_Should_ConvertStatusToText_When_StatusIsOK()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 0,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $text = $deploymentPresenter->statusText();

        $this->assertEquals('success', $text);
    }

    public function test_Should_ConvertStatusToText_When_StatusIsNg()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 1,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $text = $deploymentPresenter->statusText();

        $this->assertEquals('failure', $text);
    }

    public function test_Should_ConvertStatusToText_When_StatusIsNotDetermined()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => null,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $text = $deploymentPresenter->statusText();

        $this->assertEquals('running', $text);
    }

    public function test_Should_ConvertMessageToHtmlSnippet()
    {
        $deployment = factory(Deployment::class)->make([
            'message'    => 'Message',
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->message();

        $this->assertEquals('<span style="background-color: black; color: white">Message</span>', $html);
    }

    public function test_Should_ConvertMessageToText()
    {
        $deployment = factory(Deployment::class)->make([
            'message'    => 'Message',
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->messageText();

        $this->assertEquals('Message', $html);
    }
}
