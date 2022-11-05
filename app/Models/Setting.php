<?php
declare(strict_types=1);

namespace App\Models;

use Ngmy\EloquentSerializedLob\SerializedLobTrait;

/**
 * Class Setting
 * @package App\Models
 */
class Setting extends BaseModel
{

    use SerializedLobTrait;

    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'attributes',
    ];

    /**
     * @inheritDoc
     */
    protected function getSerializationColumn(): string
    {
        return 'attributes';
    }

    /**
     * @inheritDoc
     */
    protected function getSerializationType(): string
    {
        return \Ngmy\EloquentSerializedLob\Serializers\JsonSerializer::class;
    }

    /**
     * @inheritDoc
     */
    protected function getDeserializationType(): string
    {
        if ($this->type === 'mail') {
            return \App\Entities\Setting\MailSettingEntity::class;
        }
    }
}
