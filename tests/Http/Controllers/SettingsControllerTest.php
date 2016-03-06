<?php

class SettingsControllerTest extends TestCase
{
    use Tests\Helpers\ControllerTestHelper;

    use Tests\Helpers\MockeryHelper;

    protected $mockMailSettingRepository;

    protected $mockMailSettingForm;

    protected $mockMailSettingEntity;

    public function setUp()
    {
        parent::setUp();

        Session::start();

        $user = $this->mockPartial('App\Models\User');
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockMailSettingRepository = $this->mock('App\Repositories\Setting\MailSettingInterface');
        $this->mockMailSettingForm = $this->mock('App\Services\Form\Setting\MailSettingForm');
        $this->mockMailSettingEntity = $this->mockPartial('App\Entities\Setting\MailSettingEntity');
    }

    public function test_Should_DisplayEmailSettingPage()
    {
        $this->mockMailSettingRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($this->mockMailSettingEntity);

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
