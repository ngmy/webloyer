<?php

namespace App\Models;

class ProjectAttribute extends BaseModel
{
    protected $table = 'project_attributes';

    protected $fillable = [
        'name',
        'value',
    ];

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = json_encode($value);
    }

    public function getValueAttribute($value)
    {
        return json_decode($value);
    }
}
