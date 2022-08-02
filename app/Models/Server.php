<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Server
 * @package App\Models
 */
class Server extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'servers';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'body',
    ];
}
