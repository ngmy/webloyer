<?php

namespace Tests\Unit\app\Services\Form\Server;

use App\Services\Form\Server\ServerFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class ServerFormLaravelValidatorTest extends TestCase
{
    public function test_Should_FailToValidate_When_NameFieldIsMissing()
    {
        $input = [
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $form = new ServerFormLaravelValidator($this->app['validator']);
        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_FailToValidate_When_BodyFieldIsMissing()
    {
        $input = [
            'name'        => 'Server 1',
            'description' => '',
        ];

        $form = new ServerFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function test_Should_PassToValidate_When_NameFieldAndBodyFieldAreValid()
    {
        $input = [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $form = new ServerFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }
}
