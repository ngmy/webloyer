<?php

namespace Tests\Unit\app\Services\Form\Server;

use App\Repositories\Server\ServerInterface;
use App\Services\Form\Server\ServerForm;
use App\Services\Validation\ValidableInterface;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class ServerFormTest extends TestCase
{
    protected $mockValidator;

    protected $mockServerRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->mock(ValidableInterface::class);
        $this->mockServerRepository = $this->mock(ServerInterface::class);
    }

    public function testShouldSucceedToSaveWhenValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockServerRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn(true);

        $form = new ServerForm($this->mockValidator, $this->mockServerRepository);
        $result = $form->save([]);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function testShouldFailToSaveWhenValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new ServerForm($this->mockValidator, $this->mockServerRepository);
        $result = $form->save([]);

        $this->assertFalse($result, 'Expected save to fail.');
    }

    public function testShouldSucceedToUpdateWhenValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockServerRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $form = new ServerForm($this->mockValidator, $this->mockServerRepository);
        $result = $form->update([]);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function testShouldFailToUpdateWhenValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new ServerForm($this->mockValidator, $this->mockServerRepository);
        $result = $form->update([]);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function testShouldGetValidationErrors()
    {
        $this->mockValidator
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag());

        $form = new ServerForm($this->mockValidator, $this->mockServerRepository);
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
