<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseEloquent extends Model
{
    protected function nullIfBlank($value)
    {
        return trim($value) !== '' ? $value : null;
    }
}
