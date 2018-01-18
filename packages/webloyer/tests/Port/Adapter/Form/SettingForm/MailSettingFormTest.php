<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm\MailSettingForm;
use Tests\Helpers\MockeryHelper;
use TestCase;

class MailSettingFormTest extends TestCase
{
    use MockeryHelper;

    private $validator;

    private $settingService;

    private $mailSettingForm;

    private $inputToUpdate = [
        'driver'          => null,
        'from_address'    => null,
        'from_name'       => null,
        'smtp_host'       => null,
        'smtp_port'       => null,
        'smtp_encryption' => null,
        'smtp_username'   => null,
        'smtp_password'   => null,
        'sendmail_path'   => null,
    ];

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->mock(ValidableInterface::class);
        $this->settingService = $this->mock(SettingService::class);
        $this->mailSettingForm = new MailSettingForm(
            $this->validator,
            $this->settingService
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_SucceedToUpdate_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->settingService
            ->shouldReceive('saveMailSetting');

        $actualResult = $this->mailSettingForm->update($this->inputToUpdate);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->mailSettingForm->update($this->inputToUpdate);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $expectedResult = new MessageBag();

        $this->validator
            ->shouldReceive('errors')
            ->andReturn($expectedResult);

        $actualResult = $this->mailSettingForm->errors();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
