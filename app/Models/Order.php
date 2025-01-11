<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total', 'status', 'order_type', 'user_email', 'user_phone', 'user_name',
        'item_price' , 'item_fee', 'fee_name', 'item_name', 'sub_item_name',
        'service_id', 'amount', 'wallet_before', 'wallet_after',
        'currency_id', 'item_id', 'sub_item_id', 'revenue', 'is_external', 'external_order_id', 'uuid', 'reply_msg'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function subItem()
    {
        return $this->belongsTo(SubItem::class, 'sub_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subItems()
    {
        return $this->hasMany(OrderSubItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('quantity'); // Adjust if necessary
    }
}
