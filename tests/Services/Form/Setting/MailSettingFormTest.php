<?php

use App\Services\Form\Setting\MailSettingForm;

class MailSettingFormTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockValidator;

    protected $mockMailSettingRepository;

    public function setUp()
    {
        parent::setUp();

        $this->mockValidator = $this->mock('App\Services\Validation\ValidableInterface');
        $this->mockMailSettingRepository = $this->mock('App\Repositories\Setting\MailSettingInterface');
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

        $this->mockMailSettingRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $form = new MailSettingForm($this->mockValidator, $this->mockMailSettingRepository);
        $result = $form->update([]);

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

        $form = new MailSettingForm($this->mockValidator, $this->mockMailSettingRepository);
        $result = $form->update([]);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $this->mockValidator
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new Illuminate\Support\MessageBag);

        $form = new MailSettingForm($this->mockValidator, $this->mockMailSettingRepository);
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
