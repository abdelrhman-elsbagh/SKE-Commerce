<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Partner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'ar_name',
        'ar_description',
        'facebook',
        'whatsapp',
        'insta',
        'telegram',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('partner_images')->singleFile();
    }
}
