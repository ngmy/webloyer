<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use TestCase;

class MailSettingSmtpEncryptionTest extends TestCase
{
    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createMailSettingSmtpEncryption(),
            $this->createMailSettingSmtpEncryption(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createMailSettingSmtpEncryption(),
            $this->createMailSettingSmtpEncryption([
                'value' => 'tls',
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createMailSettingSmtpEncryption(array $params = [])
    {
        $value = 'ssl';

        extract($params);

        return new MailSettingSmtpEncryption(
            $value
        );
    }
}
