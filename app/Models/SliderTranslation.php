<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SliderTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'sub_title', 'hero_badge', 'buttons', 'stat_card'];

    protected $casts = [
        'buttons'  => 'array',
        'stat_card' => 'array',
    ];
}