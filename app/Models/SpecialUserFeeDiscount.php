<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialUserFeeDiscount extends Model
{
    use HasFactory;

    protected $table = 'special_user_fees_discounts';

    protected $fillable = [
        'user_id',
        'fee',
        'discount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
