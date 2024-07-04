<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessClientWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_client_id',
        'balance',
        'increase_status'
    ];

    public function businessClient()
    {
        return $this->belongsTo(BusinessClient::class);
    }
}
