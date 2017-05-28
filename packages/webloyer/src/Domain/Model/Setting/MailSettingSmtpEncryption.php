<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Common\Enum\EnumTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class MailSettingSmtpEncryption extends AbstractValueObject
{
    use EnumTrait;

    const ENUM = [
        'tls' => 'tls',
        'ssl' => 'ssl',
    ];

    public function equals($object)
    {
        return $object == $this;
    }
}
