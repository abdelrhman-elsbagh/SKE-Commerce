<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sub_item_id',
        'item_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subItem()
    {
        return $this->belongsTo(SubItem::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
