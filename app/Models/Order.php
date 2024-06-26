<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total', 'status', 'order_type'
    ];

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
