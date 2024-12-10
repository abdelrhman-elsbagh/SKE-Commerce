<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SubItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'item_id',
        'name',
        'description',
        'amount',
        'price',
        'service_id',
        'domain',
        'external_user_id',
        'user_id',
        'external_id',
        'fee_amount',
        'original_price',
        'external_item_id',
        'is_custom',
        'minimum_amount',
        'max_amount',
        'custom_price',
        'custom_amount',
        'client_store_id',
        'status',
    ];

    public function clientStore()
    {
        return $this->belongsTo(ClientStore::class, 'client_store_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
