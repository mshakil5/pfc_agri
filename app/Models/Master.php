<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    protected $guarded = [];

    protected $casts = [
        'translations' => 'array',
    ];

    public function getTranslation($locale, $field)
    {
        $translations = $this->translations ?? [];
        return $translations[$locale][$field] ?? $this->{$field} ?? null;
    }
}
