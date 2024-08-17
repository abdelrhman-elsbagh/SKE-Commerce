<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    // protected $table = 'clients';

    // The attributes that are mass assignable
    protected $fillable = ['name', 'email', 'phone'];

    // Define the relationship with the Account model
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'client_currency');
    }
}
