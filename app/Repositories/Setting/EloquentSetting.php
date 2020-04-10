<?php

namespace App\Repositories\Setting;

use App\Repositories\AbstractEloquentRepository;
use App\Repositories\Setting\SettingInterface;
use App\Entities\Setting\MailSettingEntity;
use Illuminate\Database\Eloquent\Model;

class EloquentSetting extends AbstractEloquentRepository implements SettingInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $setting
     * @return void
     */
    public function __construct(Model $setting)
    {
        $this->model = $setting;
    }

    /**
     * Get a model by setting type. If a model does not exist, create a new model.
     *
     * @param string $id Setting type
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function byType($type)
    {
        $setting = $this->model->where('type', $type)->first();

        if (!is_null($setting)) {
            return $setting;
        }

        if ($type === 'mail') {
            $attributes = new MailSettingEntity();
            $attributes->setDriver('smtp');
            $attributes->setFrom([
                'address' => 'webloyer@example.com',
                'name'    => 'Webloyer',
            ]);
            $attributes->setSmtpHost('smtp.mailgun.org');
            $attributes->setSmtpPort(587);
            $attributes->setSmtpEncryption('tls');
            $attributes->setSendmailPath('/usr/sbin/sendmail -bs');
        }

        return $this->model->create([
            'type'       => $type,
            'attributes' => $attributes,
        ]);
    }

    /**
     * Update an existing model has a same type.
     *
     * @param array $data Data to update a model
     * @return boolean
     */
    public function updateByType(array $data)
    {
        $setting = $this->model->where('type', $data['type'])->first();

        $setting->update($data);

        return true;
    }
}
