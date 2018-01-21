<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\NullMailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use TestCase;

class NullMailSettingSmtpEncryptionTest extends TestCase
{
    public function test_Should_GetInstance_When_FirstTime()
    {
        $actualResult = NullMailSettingSmtpEncryption::getInstance();

        $this->assertInstanceOf(NullMailSettingSmtpEncryption::class, $actualResult);
    }

    public function test_Should_GetInstance_When_AfterSecondTime()
    {
        $actualResult = NullMailSettingSmtpEncryption::getInstance();
        $actualResult = NullMailSettingSmtpEncryption::getInstance();

        $this->assertInstanceOf(NullMailSettingSmtpEncryption::class, $actualResult);
    }

    public function test_Should_GetValueReturnNull()
    {
        $nullMailSettingSmtpEncryption = $this->createNullMailSettingSmtpEncryption();

        $actualResult = $nullMailSettingSmtpEncryption->value();

        $this->assertNull($actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createNullMailSettingSmtpEncryption(),
            $this->createNullMailSettingSmtpEncryption(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createNullMailSettingSmtpEncryption(),
            MailSettingSmtpEncryption::ssl(),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createNullMailSettingSmtpEncryption(array $params = [])
    {
        return NullMailSettingSmtpEncryption::getInstance();
    }
}
