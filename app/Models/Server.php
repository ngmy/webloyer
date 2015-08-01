<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends BaseModel
{
    protected $table = 'servers';

    protected $fillable = [
        'name',
        'description',
        'body',
    ];
}
