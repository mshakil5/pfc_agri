<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $fillable = ['title', 'name', 'region', 'phone', 'website_url', 'lat', 'lng', 'services', 'status'];

    protected $casts = [
        'services' => 'array', 
    ];

    public function getCategoriesAttribute()
    {
        return Category::whereIn('id', $this->services ?? [])->get();
    }

}