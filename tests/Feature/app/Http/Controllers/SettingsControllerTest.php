<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Entities\Setting\MailSettingEntity;
use App\Http\Middleware\ApplySettings;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Setting\SettingInterface;
use App\Services\Form\Setting\MailSettingForm;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use ControllerTestHelper;

    protected $mockSettingRepository;

    protected $mockMailSettingForm;

    protected $mockMailSettingEntity;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->partialMock(User::class);
        $user->shouldReceive('hasPermission')
            ->andReturn(true);
        $this->auth($user);

        $this->mockSettingRepository = $this->mock(SettingInterface::class);
        $this->mockMailSettingForm = $this->mock(MailSettingForm::class);
        $this->mockSettingModel = $this->partialMock(Setting::class);
        $this->mockMailSettingEntity = $this->mock(MailSettingEntity::class);
    }

    public function test_Should_DisplayEmailSettingPage()
    {
        $this->mockMailSettingEntity
            ->shouldReceive('getDriver')
            ->once()
            ->shouldReceive('getFrom')
            ->twice()
            ->shouldReceive('getSmtpHost')
            ->once()
            ->shouldReceive('getSmtpPort')
            ->once()
            ->shouldReceive('getSmtpEncryption')
            ->once()
            ->shouldReceive('getSmtpUsername')
            ->once()
            ->shouldReceive('getSmtpPassword')
            ->once()
            ->shouldReceive('getSendmailPath')
            ->once();
        $this->mockSettingModel
            ->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockMailSettingEntity);
        $this->mockSettingRepository
            ->shouldReceive('byType')
            ->once()
            ->andReturn($this->mockSettingModel);

        $response = $this->get('settings/email');

        $response->assertStatus(200);
        $response->assertViewHas('settings');
    }

    public function test_Should_RedirectToEmailSettingPage_When_EmailSettingProcessIsRequestedAndEmailSettingProcessSucceeds()
    {
        $this->mockMailSettingForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $response = $this->post('settings/email');

        $response->assertRedirect('settings/email');
    }

    public function test_Should_RedirectToEmailSettingPage_When_EmailSettingProcessIsRequestedAndEmailSettingProcessFails()
    {
        $this->mockMailSettingForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $this->mockMailSettingForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $response = $this->post('settings/email');

        $response->assertRedirect('settings/email');
    }
}
