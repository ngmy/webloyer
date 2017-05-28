<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\EloquentSerializedLob\SerializedLobTrait;
use Ngmy\EloquentSerializedLob\Serializer\JsonSerializer;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;

class Setting extends AbstractBaseEloquent
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
        return JsonSerializer::class;
    }

    protected function serializedLobDeserializeType()
    {
        if ($this->type === 'mail') {
            return MailSetting::class;
        }
    }
}
