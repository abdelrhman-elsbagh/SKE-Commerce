<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'issued_in',
        'notes',
        'amount',
        'price',
        'sub_item_id',
    ];

    public function subItem()
    {
        return $this->belongsTo(SubItem::class); // Adjust relationship as needed (e.g., hasMany)
    }
}
