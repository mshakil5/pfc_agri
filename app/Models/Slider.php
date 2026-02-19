<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['title', 'sub_title', 'hero_badge', 'buttons', 'stat_card'];

    protected $fillable = [
        'link', 'slug', 'image', 'status', 'serial',
        'created_by', 'updated_by', 'deleted_by'
    ];
}