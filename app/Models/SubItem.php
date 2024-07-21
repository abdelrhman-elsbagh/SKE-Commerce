<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SubItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['item_id', 'name', 'description', 'amount', 'price', 'service_id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function orderSubItem()
    {
        return $this->hasOne(OrderSubItem::class, 'sub_item_id');
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderSubItem::class, 'sub_item_id', 'id', 'id', 'order_id');
    }


    public function orderSubItems()
    {
        return $this->hasMany(OrderSubItem::class, 'sub_item_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
    }
}
