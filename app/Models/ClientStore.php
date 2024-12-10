<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientStore extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'domain', 'secret_key', 'status', 'email'];

    public function subItems()
    {
        return $this->hasMany(SubItem::class, 'client_store_id');
    }
}
