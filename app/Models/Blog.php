<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model implements TranslatableContract
{
    use Translatable;

    // Attributes that vary by language
    public $translatedAttributes = ['title', 'slug', 'excerpt', 'description'];

    // Global attributes
    protected $fillable = ['image', 'author_name', 'published_at', 'status'];
}