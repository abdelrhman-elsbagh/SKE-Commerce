<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSubItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'sub_item_id', 'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function subItem()
    {
        return $this->belongsTo(SubItem::class);
    }
}
