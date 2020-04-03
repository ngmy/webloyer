<?php

namespace App\Models;

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
        return \Ngmy\EloquentSerializedLob\Serializer\JsonSerializer::class;
    }

    protected function getDeserializationType(): string
    {
        if ($this->type === 'mail') {
            return \App\Entities\Setting\MailSettingEntity::class;
        }
    }
}
