<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm\MailSettingFormLaravelValidator;
use TestCase;

class MailSettingFormLaravelValidatorTest extends TestCase
{
    private $mailSettingFormLaravelValidator;

    public function setUp()
    {
        parent::setUp();

        $this->mailSettingFormLaravelValidator = new MailSettingFormLaravelValidator($this->app['validator']);
    }

    public function test_Should_PassToValidate_When_AllFieldsIsValid()
    {
        $input = [
            'driver'            => 'smtp',
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 587,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }

    public function test_Should_FailToValidate_When_DriverFieldIsMissing()
    {
        $input = [
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 587,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_DriverFieldIsInvalid()
    {
        $input = [
            'driver'            => 'invalid',
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 587,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_FromAddressFieldIsMissing()
    {
        $input = [
            'driver'            => 'smtp',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 587,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_FromAddressFieldIsInvalid()
    {
        $input = [
            'driver'            => 'smtp',
            'from_address'      => 'invalid',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 587,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_PassToValidate_When_SmtpPortFieldIsEqualToMin()
    {
        $input = [
            'driver'            => 'smtp',
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 0,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }

    public function test_Should_PassToValidate_When_SmtpPortFieldIsEqualToMax()
    {
        $input = [
            'driver'            => 'smtp',
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 65535,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }

    public function test_Should_FailToValidate_When_SmtpPortFieldIsLessThanMin()
    {
        $input = [
            'driver'            => 'smtp',
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => -1,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_SmtpPortFieldIsGreaterThanMax()
    {
        $input = [
            'driver'            => 'smtp',
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 65536,
            'smtp_encryption'   => 'tls',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_SmtpEncryptionFieldIsInvalid()
    {
        $input = [
            'driver'            => 'smtp',
            'from_address'      => 'from_address@example.com',
            'from_name'         => 'from_name',
            'smtp_host'         => 'localhost',
            'smtp_port'         => 587,
            'smtp_encryption'   => 'invalid',
            'smtp_username'     => 'username@example.com',
            'smtp_password'     => 'password',
            'sendmail_path'     => '/usr/sbin/sendmail -bs',
        ];

        $actualResult = $this->mailSettingFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->mailSettingFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }
}
