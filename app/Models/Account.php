<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['payment_status', 'amount', 'client_id', 'currency_id', 'notes', 'uuid', 'mask'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    // Define the relationship with the Client model
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'account_currency');
    }



}
