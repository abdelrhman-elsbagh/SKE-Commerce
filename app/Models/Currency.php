<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['currency', 'price', 'name', 'status'];


    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_currency');
    }
}
