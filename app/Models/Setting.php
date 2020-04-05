<?php

namespace App\Models;

use App\Entities\Setting\MailSettingEntity;
use Ngmy\EloquentSerializedLob\SerializedLobTrait;

class Setting extends BaseModel
{
    use SerializedLobTrait;

    protected $table = 'settings';

    protected $fillable = [
        'type',
        'attributes',
    ];

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
        if ($this->type == 'mail') {
            return MailSettingEntity::class;
        }
    }
}
