<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'slug', 'price', 'image', 'category_id', 'tag_id', 'short_description', 'long_description', 'status', 'stock_status', 'show_in_menu'];

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translateOrNew($locale)
    {
        $translation = $this->translations->where('locale', $locale)->first();
        if (!$translation) {
            $translation = new ProductTranslation();
            $translation->product_id = $this->id;
            $translation->locale = $locale;
        }
        return $translation;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}