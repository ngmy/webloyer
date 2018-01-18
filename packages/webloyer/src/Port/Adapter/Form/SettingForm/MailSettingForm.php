<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm;

use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;

class MailSettingForm
{
    private $validator;

    private $settingService;

    /**
     * Create a new form service instance.
     *
     * @param \Ngmy\Webloyer\Common\Validation\ValidableInterface        $validator
     * @param \Ngmy\Webloyer\Webloyer\Application\Setting\SettingService $settingService
     * @return void
     */
    public function __construct(ValidableInterface $validator, SettingService $settingService)
    {
        $this->validator = $validator;
        $this->settingService = $settingService;
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

        $from = [
            'address' => $input['from_address'],
            'name'    => $input['from_name'],
        ];

        $this->settingService->saveMailSetting(
            $input['driver'],
            $from,
            $input['smtp_host'],
            $input['smtp_port'],
            $input['smtp_encryption'],
            $input['smtp_username'],
            $input['smtp_password'],
            $input['sendmail_path']
        );

        return true;
    }

    /**
     * Return validation errors.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test whether form validator passes.
     *
     * @param array $input Data to test whether form validator passes
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
