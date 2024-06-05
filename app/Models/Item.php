<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Item extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'description', 'price_in_diamonds', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getPriceInUsdAttribute()
    {
        $rate = DiamondRate::where('diamonds', $this->price_in_diamonds)->first();
        return $rate ? $rate->usd : null;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }
}
