<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm;

use Ngmy\Webloyer\Common\Port\Adapter\Validation\AbstractLaravelValidator;

class MailSettingFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'driver'            => 'required|in:smtp,mail,sendmail',
        'from_address'      => 'required|email',
        'from_name'         => 'sometimes',
        'smtp_host'         => 'sometimes',
        'smtp_port'         => 'sometimes|integer|min:0|max:65535',
        'smtp_encryption'   => 'sometimes|in:tls,ssl',
        'smtp_username'     => 'sometimes',
        'smtp_password'     => 'sometimes',
        'sendmail_path'     => 'sometimes',
    ];
}
