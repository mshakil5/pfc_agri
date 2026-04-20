<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'title',
        'short_description',
        'long_description',
        'features', 'specs'
    ];

    // THIS FIXES THE ERROR: Tells Laravel to convert the array to JSON for the DB
    protected $casts = [
        'features' => 'array',
    ];
}