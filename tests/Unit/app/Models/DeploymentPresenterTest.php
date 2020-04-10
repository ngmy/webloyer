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
    public function testShouldConvertStatusToHtmlSnippetWhenStatusIsOK()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 0,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->status();

        $this->assertEquals('<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>', $html);
    }

    public function testShouldConvertStatusToHtmlSnippetWhenStatusIsNg()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 1,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->status();

        $this->assertEquals('<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>', $html);
    }

    public function testShouldConvertStatusToHtmlSnippetWhenStatusIsUnknown()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => null,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->status();

        $this->assertEquals('<span></span>', $html);
    }

    public function testShouldConvertStatusToTextWhenStatusIsOK()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 0,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $text = $deploymentPresenter->statusText();

        $this->assertEquals('success', $text);
    }

    public function testShouldConvertStatusToTextWhenStatusIsNg()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => 1,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $text = $deploymentPresenter->statusText();

        $this->assertEquals('failure', $text);
    }

    public function testShouldConvertStatusToTextWhenStatusIsNotDetermined()
    {
        $deployment = factory(Deployment::class)->make([
            'status'     => null,
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $text = $deploymentPresenter->statusText();

        $this->assertEquals('running', $text);
    }

    public function testShouldConvertMessageToHtmlSnippet()
    {
        $deployment = factory(Deployment::class)->make([
            'message'    => 'Message',
        ]);

        $converter = new AnsiToHtmlConverter();
        $deploymentPresenter = new DeploymentPresenter($deployment, $converter);

        $html = $deploymentPresenter->message();

        $this->assertEquals('<span style="background-color: black; color: white">Message</span>', $html);
    }

    public function testShouldConvertMessageToText()
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
