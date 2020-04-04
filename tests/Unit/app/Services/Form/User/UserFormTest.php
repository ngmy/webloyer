<?php

namespace Tests\Unit\app\Services\Form\User;

use App\Models\User;
use App\Repositories\User\UserInterface;
use App\Services\Form\User\UserForm;
use App\Services\Validation\ValidableInterface;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class UserFormTest extends TestCase
{
    protected $mockValidator;

    protected $mockUserRepository;

    protected $mockUserModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->mock(ValidableInterface::class);
        $this->mockUserRepository = $this->mock(UserInterface::class);
        $this->mockUserModel = $this->partialMock(User::class);
    }

    public function test_Should_SucceedToSave_When_ValidationPassesAndRoleFieldIsNotSpecified()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockUserRepository
            ->shouldReceive('create')
            ->once();

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->save([]);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_SucceedToSave_When_ValidationPassesAndRoleFieldIsSpecified()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $user = $this->mockUserModel
            ->shouldReceive('assignRole')
            ->once()
            ->mock();
        $this->mockUserRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($user);

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->save(['role' => []]);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->save([]);

        $this->assertFalse($result, 'Expected save to fail.');
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

        $this->mockUserRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
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

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->update([]);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_SucceedToUpdatePassword_When_ValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockUserRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->update([]);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function test_Should_FailToUpdatePassword_When_ValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->update([]);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_SucceedToUpdateRole_When_ValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockUserRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->update([]);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function test_Should_FailToUpdateRole_When_ValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->update([]);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $this->mockValidator
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag());

        $form = new UserForm($this->mockValidator, $this->mockUserRepository);
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
