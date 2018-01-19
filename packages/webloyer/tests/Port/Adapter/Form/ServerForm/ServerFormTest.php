<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm\ServerForm;
use Tests\Helpers\MockeryHelper;
use TestCase;

class ServerFormTest extends TestCase
{
    use MockeryHelper;

    private $validator;

    private $serverService;

    private $serverForm;

    private $inputForSave = [
        'name'        => null,
        'description' => null,
        'body'        => null,
    ];

    private $inputForUpdate = [
        'id'                  => null,
        'name'                => null,
        'description'         => null,
        'body'                => null,
        'concurrency_version' => null,
    ];

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->mock(ValidableInterface::class);
        $this->serverService = $this->mock(ServerService::class);
        $this->serverForm = new ServerForm(
            $this->validator,
            $this->serverService
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_SucceedToSave_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->serverService
            ->shouldReceive('saveServer');

        $actualResult = $this->serverForm->save($this->inputForSave);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->serverForm->save($this->inputForSave);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->serverService
            ->shouldReceive('saveServer');

        $actualResult = $this->serverForm->update($this->inputForUpdate);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->serverForm->update($this->inputForUpdate);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $expectedResult = new MessageBag();

        $this->validator
            ->shouldReceive('errors')
            ->andReturn($expectedResult);

        $actualResult = $this->serverForm->errors();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
