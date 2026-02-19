<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id', 'status'];

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translateOrNew($locale)
    {
        $translation = $this->translations->where('locale', $locale)->first();
        if (!$translation) {
            $translation = new CategoryTranslation();
            $translation->category_id = $this->id;
            $translation->locale = $locale;
        }
        return $translation;
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}