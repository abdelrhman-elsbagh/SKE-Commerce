<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStyle extends Model
{
    use HasFactory;

    protected $table = 'item_style';
    protected $fillable = ['xl', 'lg', 'md', 'sm'];
}
