<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseEloquent extends Model
{
    protected function nullIfBlank($value)
    {
        return trim($value) !== '' ? $value : null;
    }
}
