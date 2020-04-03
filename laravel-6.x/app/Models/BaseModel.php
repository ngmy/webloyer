<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected function nullIfBlank($value)
    {
        return trim($value) !== '' ? $value : null;
    }
}
