<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $table = 'research';

    protected $fillable = [
        'user_id',
        'title',
        'authors',
        'abstract',
        'year',
        'doi',
    ];
}
