<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public $translatable = ['title', 'short_description', 'long_description', 'features'];
    
    public $translationModel = ProductTranslation::class;

    protected $fillable = [
        'title', 'slug', 'category_id', 'tag_id', 'price', 'image', 'images', 'short_description', 'long_description', 'status', 'downloads'
    ];

    protected $casts = [
        'images' => 'array','downloads' => 'array',
    ];

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