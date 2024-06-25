<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Item extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'description', 'price_in_diamonds', 'category_id', 'status', 'title', 'title_type',];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function subItems()
    {
        return $this->hasMany(SubItem::class);
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

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity'); // Adjust if necessary
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
