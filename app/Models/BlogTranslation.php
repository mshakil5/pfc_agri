<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTranslation extends Model
{
    public $timestamps = false; // Usually, translations don't need their own timestamps
    
    // Add the fields that were causing the error here
    protected $fillable = ['title', 'slug', 'excerpt', 'description'];
}
