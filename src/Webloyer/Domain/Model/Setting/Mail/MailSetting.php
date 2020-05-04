<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Setting\Mail;

class MailSetting
{
    /** @var MailSettingName */
    private $name;
    /** @var MailSettingValue */
    private $value;

    public function __construct(
        MailSettingName $name,
        MailSettingValue $value
    ) {
        $this->name = $name;
        $this->value = $value;
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function value()
    {
        return $this->value->value();
    }
}
