<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SubItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['item_id', 'name', 'description', 'amount', 'price'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
    }
}
