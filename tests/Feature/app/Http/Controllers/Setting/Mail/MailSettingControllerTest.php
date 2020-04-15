<?php

declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\Setting;

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

class MailSettingControllerTest extends TestCase
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

    public function testShouldDisplayEmailSettingPage()
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

    public function testShouldRedirectToEmailSettingPageWhenEmailSettingProcessIsRequestedAndEmailSettingProcessSucceeds()
    {
        $this->mockMailSettingForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $response = $this->post('settings/email');

        $response->assertRedirect('settings/email');
    }

    public function testShouldRedirectToEmailSettingPageWhenEmailSettingProcessIsRequestedAndEmailSettingProcessFails()
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
