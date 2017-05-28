<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Ngmy\Webloyer\Common\Enum\EnumTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class KeepLastDeployment extends AbstractValueObject
{
    use EnumTrait;

    const ENUM = [
        'on'  => 1,
        'off' => 0,
    ];

    public function displayName()
    {
        if ($this->isOn()) {
            return 'On';
        }
        if ($this->isOff()) {
            return 'Off';
        }
    }

    public function isOn()
    {
        return $this->equals(self::on());
    }

    public function isOff()
    {
        return $this->equals(self::off());
    }

    public function equals($object)
    {
        return $object == $this;
    }
}
