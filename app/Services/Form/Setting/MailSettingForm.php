<?php
declare(strict_types=1);

namespace App\Services\Form\Setting;

use App\Services\Validation\ValidableInterface;
use App\Repositories\Setting\SettingInterface;

/**
 * Class MailSettingForm
 * @package App\Services\Form\Setting
 */
class MailSettingForm
{
    /**
     * @var ValidableInterface
     */
    protected ValidableInterface $validator;

    /**
     * @var SettingInterface
     */
    protected SettingInterface $setting;

    /**
     * Create a new form service instance.
     *
     * @param ValidableInterface $validator
     * @param SettingInterface $setting
     * @return void
     */
    public function __construct(ValidableInterface $validator, SettingInterface $setting)
    {
        $this->validator = $validator;
        $this->setting   = $setting;
    }

    /**
     * Update an existing setting.
     *
     * @param array $input Data to update a setting
     * @return boolean
     */
    public function update(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

        foreach ($input as $key => $value) {
            if ($value === '') {
                $input[$key] = null;
            }
        }

        $mailSetting = new \App\Entities\Setting\MailSettingEntity;
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

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test whether form validator passes.
     *
     * @param array $input
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
