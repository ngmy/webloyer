<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Eloquents\Setting;

use Illuminate\Database\Eloquent\{
    Builder,
    Model,
};
use Ngmy\EloquentSerializedLob\SerializedLobTrait;
use Webloyer\Domain\Model\Setting as SettingDomainModel;

class Setting extends Model
{
    use SerializedLobTrait;

    private const SETTING_TYPE_MAIL = 'mail';

    /** @var array<int, string> */
    protected $fillable = [
        'type',
        'attributes',
    ];

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeMailSetting(Builder $query): Builder
    {
        return $query->where('type', self::SETTING_TYPE_MAIL);
    }

    public function isMailSetting(): bool
    {
        return $this->type == self::SETTING_TYPE_MAIL;
    }

    public function toMailSettingEntities(): SettingDomainModel\Mail\MailSettings
    {
        if ($this->isMailSetting()) {
            return new SettingDomainModel\Mail\MailSettings(
                ...array_map(function (string $key) {
                    return $mailSetting = new SettingDomainModel\Mail\MailSetting(
                        new SettingDomainModel\Mail\MailSettingKey($key),
                        $this->toMailSettingEntity($key)
                    );
                }, array_keys($this->attributes))
            );
        }
    }

    public function toMailSettingEntity(string $key): SettingDomainModel\Mail\MailSetting
    {
        $value = $this->attributes[$key];
        if ($key == 'driver') {
            return new SettingDomainModel\Mail\MailSettingDriver($value);
        } elseif ($key == 'from') {
            return new SettingDomainModel\Mail\MailSettingFrom($value);
        }
    }

    protected function getSerializationColumn(): string
    {
        return 'attributes';
    }

    protected function getSerializationType(): string
    {
        return 'json';
    }

    protected function getDeserializationType(): string
    {
        return 'array';
    }
}
