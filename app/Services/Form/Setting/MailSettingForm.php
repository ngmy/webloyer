<?php

namespace App\Services\Form\Setting;

use App\Services\Validation\ValidableInterface;
use App\Repositories\Setting\MailSettingInterface;

class MailSettingForm
{
    protected $validator;

    protected $setting;

    /**
     * Create a new form service instance.
     *
     * @param \App\Services\Validation\ValidableInterface    $validator
     * @param \App\Repositories\Setting\MailSettingInterface $setting
     * @return void
     */
    public function __construct(ValidableInterface $validator, MailSettingInterface $setting)
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

        $this->setting->update($input);

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
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
