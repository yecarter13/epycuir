<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'specifications',
        'price', 'old_price', 'sku', 'compatibility', 'image', 'gallery_images',
        'brand', 'is_new', 'is_active', 'stock_quantity', 'rating', 'review_count',
        'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_new' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'rating' => 'decimal:1',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageAttribute($value)
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) {
            return route('image.proxy', ['url' => $value]);
        }
        if (str_starts_with($value, '//')) return $value;
        if (str_starts_with($value, '/')) return asset(substr($value, 1));
        return asset('images/' . $value);
    }

    public function getGalleryImagesAttribute($value)
    {
        $images = json_decode($value, true);
        if (!is_array($images)) return [];
        return array_map(function ($url) {
            if (!$url) return null;
            if (str_starts_with($url, 'http')) {
                return route('image.proxy', ['url' => $url]);
            }
            if (str_starts_with($url, '//')) return $url;
            if (str_starts_with($url, '/')) return asset(substr($url, 1));
            return asset('images/' . $url);
        }, $images);
    }

    protected static function booted(): void
    {
        static::creating(function (self $product) {
            $baseSlug = Str::slug($product->name);
            $slug = $baseSlug;
            $counter = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $product->slug = $slug;
        });

        static::updating(function (self $product) {
            if ($product->isDirty('name')) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $product->slug = $slug;
            }
        });
    }
}
