<?php

namespace Tests\Unit\app\Services\Form\Setting;

use App\Services\Form\Setting\MailSettingFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class MailSettingFormLaravelValidatorTest extends TestCase
{
    public function testShouldPassToValidateWhenAllFieldsIsValid()
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

    public function testShouldFailToValidateWhenDriverFieldIsMissing()
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenDriverFieldIsInvalid()
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenFromAddressFieldIsMissing()
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenFromAddressFieldIsInvalid()
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldPassToValidateWhenSmtpPortFieldIsEqualToMin()
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

    public function testShouldPassToValidateWhenSmtpPortFieldIsEqualToMax()
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

    public function testShouldFailToValidateWhenSmtpPortFieldIsLessThanMin()
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenSmtpPortFieldIsGreaterThanMax()
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenSmtpEncryptionFieldIsInvalid()
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
        $this->assertInstanceOf(MessageBag::class, $errors);
    }
}
