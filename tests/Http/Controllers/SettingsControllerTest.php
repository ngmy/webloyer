<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApplySettings;
use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\NullMailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm\MailSettingForm;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\MockeryHelper;
use TestCase;

class SettingsControllerTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    private $settingService;

    private $mailSettingForm;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->mock(User::class);
        $user->shouldReceive('can')->andReturn(true);
        $user->shouldReceive('name');
        $this->auth($user);

        $this->settingService = $this->mock(SettingService::class);
        $this->mailSettingForm = $this->mock(MailSettingForm::class);

        $this->app->instance(SettingService::class, $this->settingService);
        $this->app->instance(MailSettingForm::class, $this->mailSettingForm);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_DisplayEmailSettingPage()
    {
        $mailSetting = $this->createMailSetting();

        $this->settingService
            ->shouldReceive('getMailSetting')
            ->withNoArgs()
            ->andReturn($mailSetting)
            ->once();

        $this->get('settings/email');

        $this->assertResponseOk();
        $this->assertViewHas('mailSetting');
    }

    public function test_Should_RedirectToEmailSettingPage_When_EmailSettingProcessIsRequestedAndEmailSettingProcessSucceeds()
    {
        $this->mailSettingForm
            ->shouldReceive('update')
            ->andReturn(true)
            ->once();

        $this->post('settings/email');

        $this->assertRedirectedToRoute('settings.email');
    }

    public function test_Should_RedirectToEmailSettingPage_When_EmailSettingProcessIsRequestedAndEmailSettingProcessFails()
    {
        $this->mailSettingForm
            ->shouldReceive('update')
            ->andReturn(false)
            ->once();

        $this->mailSettingForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $this->post('settings/email');

        $this->assertRedirectedToRoute('settings.email');
    }

    private function createMailSetting(array $params = [])
    {
        $driver = 'smtp';
        $from = [
            'address' => '',
            'name' => '',
        ];
        $smtpHost = '';
        $smtpPort = 25;
        $smtpEncryption = 'tls';
        $smtpUserName = '';
        $smtpPassword = '';
        $sendmailPath = '';

        extract($params);

        $mailSetting = $this->mock(MailSetting::class);

        $mailSetting->shouldReceive('driver')->andReturn(new MailSettingDriver($driver));
        $mailSetting->shouldReceive('from')->andReturn($from);
        $mailSetting->shouldReceive('smtpHost')->andReturn($smtpHost);
        $mailSetting->shouldReceive('smtpPort')->andReturn($smtpPort);
        $mailSetting->shouldReceive('smtpEncryption')->andReturn(new MailSettingSmtpEncryption($smtpEncryption));
        $mailSetting->shouldReceive('smtpUserName')->andReturn($smtpUserName);
        $mailSetting->shouldReceive('smtpPassword')->andReturn($smtpPassword);
        $mailSetting->shouldReceive('sendmailPath')->andReturn($sendmailPath);

        return $mailSetting;
    }
}
