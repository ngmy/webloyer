<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm\ServerFormLaravelValidator;
use TestCase;

class ServerFormLaravelValidatorTest extends TestCase
{
    private $serverFormLaravelValidator;

    public function setUp()
    {
        parent::setUp();

        $this->serverFormLaravelValidator = new ServerFormLaravelValidator($this->app['validator']);
    }

    public function test_Should_FailToValidate_When_NameFieldIsMissing()
    {
        $input = [
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $actualResult = $this->serverFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->serverFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_BodyFieldIsMissing()
    {
        $input = [
            'name'        => 'Server 1',
            'description' => '',
        ];

        $actualResult = $this->serverFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->serverFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_PassToValidate_When_NameFieldAndBodyFieldAreValid()
    {
        $input = [
            'name'        => 'Server 1',
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $actualResult = $this->serverFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->serverFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }
}
