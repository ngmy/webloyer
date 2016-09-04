<?php

use Tests\Helpers\DummyMiddleware;

class SettingsControllerTest extends TestCase
{
    use Tests\Helpers\ControllerTestHelper;

    use Tests\Helpers\MockeryHelper;

    protected $mockSettingRepository;

    protected $mockMailSettingForm;

    protected $mockMailSettingEntity;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(\App\Http\Middleware\ApplySettings::class, new DummyMiddleware);

        Session::start();

        $user = $this->mockPartial('App\Models\User');
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockSettingRepository = $this->mock('App\Repositories\Setting\SettingInterface');
        $this->mockMailSettingForm = $this->mock('App\Services\Form\Setting\MailSettingForm');
        $this->mockSettingModel = $this->mockPartial('App\Models\Setting');
        $this->mockMailSettingEntity = $this->mock('App\Entities\Setting\MailSettingEntity');
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

        $this->get('settings/email');

        $this->assertResponseOk();
        $this->assertViewHas('settings');
    }

    public function test_Should_RedirectToEmailSettingPage_When_EmailSettingProcessIsRequestedAndEmailSettingProcessSucceeds()
    {
        $this->mockMailSettingForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $this->post('settings/email');

        $this->assertRedirectedToRoute('settings.email');
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

        $this->post('settings/email');

        $this->assertRedirectedToRoute('settings.email');
    }
}
