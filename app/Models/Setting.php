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

    protected function serializedLobColumn()
    {
        return 'attributes';
    }

    protected function serializedLobSerializer()
    {
        return \Ngmy\EloquentSerializedLob\Serializer\JsonSerializer::class;
    }

    protected function serializedLobDeserializeType()
    {
        if ($this->type === 'mail') {
            return \App\Entities\Setting\MailSettingEntity::class;
        }
    }
}
