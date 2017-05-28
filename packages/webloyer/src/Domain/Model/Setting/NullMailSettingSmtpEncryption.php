<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class NullMailSettingSmtpEncryption extends AbstractValueObject
{
    private static $instance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
             self::$instance = new self;
        }
        return self::$instance;
    }

    public function value()
    {
    }

    public function equals($object)
    {
        return $object == static::$instance;
    }

    private function __construct()
    {
    }
}
