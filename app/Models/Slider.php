<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $casts = [
        'buttons' => 'array',
        'stat_card' => 'array',
    ];
}
