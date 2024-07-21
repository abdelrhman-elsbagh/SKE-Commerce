<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Config extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'description', 'whatsapp', 'telegram', 'facebook', 'fee', 'discount', 'currency', 'font'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')
            ->singleFile(); // Ensures only one logo is stored
    }
}
