<?php
declare(strict_types=1);

namespace App\Repositories\Setting;

use App\Repositories\AbstractEloquentRepository;
use App\Entities\Setting\MailSettingEntity;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentSetting
 * @package App\Repositories\Setting
 */
class EloquentSetting extends AbstractEloquentRepository implements SettingInterface
{

    /**
     * EloquentSetting constructor.
     * @param Setting $setting
     */
    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    /**
     * Get a model by setting type. If a model does not exist, create a new model.
     *
     * @param string $id Setting type
     * @return Model
     */
    public function byType($type)
    {
        $setting = $this->model->where('type', $type)->first();

        if (!is_null($setting)) {
            return $setting;
        }

        $attributes = '';

        if ($type === 'mail') {
            $attributes = new MailSettingEntity;
            $attributes->setDriver('smtp');
            $attributes->setFrom([
                'address' => 'email@example.com',
                'name'    => 'Webloyer',
            ]);
            $attributes->setSmtpHost('smtp.mailtrap.io');
            $attributes->setSmtpPort(2525);
            $attributes->setSmtpEncryption('tls');
            $attributes->setSmtpUsername('username');
            $attributes->setSmtpPassword('password');
            $attributes->setSendmailPath('');
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
