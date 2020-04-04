<?php

namespace Tests\Unit\app\Services\Form\Setting;

use App\Repositories\Setting\SettingInterface;
use App\Services\Form\Setting\MailSettingForm;
use App\Services\Validation\ValidableInterface;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class MailSettingFormTest extends TestCase
{
    protected $mockValidator;

    protected $mockSettingRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->mock(ValidableInterface::class);
        $this->mockSettingRepository = $this->mock(SettingInterface::class);
    }

    public function test_Should_SucceedToUpdate_When_ValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockSettingRepository
            ->shouldReceive('updateByType')
            ->once()
            ->andReturn(true);

        $form = new MailSettingForm($this->mockValidator, $this->mockSettingRepository);
        $result = $form->update([
            'driver'          => '',
            'from_address'    => '',
            'from_name'       => '',
            'smtp_host'       => '',
            'smtp_port'       => '',
            'smtp_encryption' => '',
            'smtp_username'   => '',
            'smtp_password'   => '',
            'sendmail_path'   => '',
        ]);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new MailSettingForm($this->mockValidator, $this->mockSettingRepository);
        $result = $form->update([]);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $this->mockValidator
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag());

        $form = new MailSettingForm($this->mockValidator, $this->mockSettingRepository);
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
