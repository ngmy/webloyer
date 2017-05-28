<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Common\Enum\EnumTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class MailSettingDriver extends AbstractValueObject
{
    use EnumTrait;

    const ENUM = [
        'smtp'     => 'smtp',
        'php'      => 'mail',
        'sendmail' => 'sendmail',
    ];

    public function equals($object)
    {
        return $object == $this;
    }
}
