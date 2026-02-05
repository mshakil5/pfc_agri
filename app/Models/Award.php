<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Award extends Model implements TranslatableContract
{
    use Translatable;

    // 1. Define which attributes are translatable
    public $translatedAttributes = ['title', 'organization', 'tag', 'description'];

    // 2. Define which attributes can be mass-assigned (non-translated ones)
    protected $fillable = ['icon', 'year'];
}