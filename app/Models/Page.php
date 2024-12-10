<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['data', 'slug', 'title', 'ar_title', 'ar_data'];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pages');
    }
}
