<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'pages', 'header_title', 'header_subtitle', 'title', 'sub_title',
        'year', 'long_description', 'image', 'amenities', 'translations'
    ];

    protected $casts = [
        'translations' => 'array',
    ];

    public function getTranslation($locale, $field)
    {
        $translations = $this->translations ?? [];
        return $translations[$locale][$field] ?? $this->{$field} ?? null;
    }
}