<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BusinessClient extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'business_name', 'phone', 'address'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'business_client_id');
    }

    public function wallet()
    {
        return $this->hasOne(BusinessClientWallet::class);
    }

    public function businessPaymentMethods()
    {
        return $this->hasMany(BusinessPaymentMethod::class);
    }

    public function businessPurchaseRequests()
    {
        return $this->hasMany(BusinessPurchaseRequest::class);
    }

}
