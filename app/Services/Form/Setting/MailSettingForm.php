<?php

namespace App\Services\Form\Setting;

use App\Entities\Setting\MailSettingEntity;
use App\Repositories\Setting\SettingInterface;

class MailSettingForm
{
    protected $setting;

    /**
     * Create a new form service instance.
     *
     * @param \App\Repositories\Setting\SettingInterface $setting
     * @return void
     */
    public function __construct(SettingInterface $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Update an existing setting.
     *
     * @param array $input Data to update a setting
     * @return boolean
     */
    public function update(array $input)
    {
        foreach ($input as $key => $value) {
            if ($value === '') {
                $input[$key] = null;
            }
        }

        $mailSetting = new MailSettingEntity();
        $mailSetting->setDriver($input['driver']);
        $mailSetting->setFrom([
            'address' => $input['from_address'],
            'name'    => $input['from_name'],
        ]);
        $mailSetting->setSmtpHost($input['smtp_host']);
        $mailSetting->setSmtpPort($input['smtp_port']);
        $mailSetting->setSmtpEncryption($input['smtp_encryption']);
        $mailSetting->setSmtpUsername($input['smtp_username']);
        $mailSetting->setSmtpPassword($input['smtp_password']);
        $mailSetting->setSendmailPath($input['sendmail_path']);

        $input['attributes'] = $mailSetting;
        $input['type'] = 'mail';

        $this->setting->updateByType($input);

        return true;
    }
}
