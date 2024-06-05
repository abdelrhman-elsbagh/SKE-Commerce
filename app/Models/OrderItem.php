<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'item_id', 'quantity', 'price_in_diamonds',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getTotalPriceInUsdAttribute()
    {
        $rate = DiamondRate::where('diamonds', $this->price_in_diamonds)->first();
        return $rate ? ($rate->usd * $this->quantity) : null;
    }
}
