<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends BaseModel
{
    protected $table = 'recipes';

    protected $fillable = [
        'name',
        'description',
        'body',
    ];
}
