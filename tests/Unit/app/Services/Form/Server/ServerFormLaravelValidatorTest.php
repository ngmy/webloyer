<?php

namespace Tests\Unit\app\Services\Form\Server;

use App\Services\Form\Server\ServerFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class ServerFormLaravelValidatorTest extends TestCase
{
    public function testShouldFailToValidateWhenNameFieldIsMissing()
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

    public function testShouldFailToValidateWhenBodyFieldIsMissing()
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

    public function testShouldPassToValidateWhenNameFieldAndBodyFieldAreValid()
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
