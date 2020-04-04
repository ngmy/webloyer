<?php

namespace Tests\Unit\app\Services\Form\Setting;

use App\Services\Form\Setting\MailSettingFormLaravelValidator;
use Tests\TestCase;

class MailSettingFormLaravelValidatorTest extends TestCase
{
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
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

        $form = new MailSettingFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }
}
