<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package App\Models
 */
class BaseModel extends Model
{
    /**
     * @param mixed $value
     * @return string|null
     */
    protected function nullIfBlank($value)
    {
        if (!is_null($value)) {
            return trim($value) !== '' ? $value : null;
        }
        return null;
    }
}
