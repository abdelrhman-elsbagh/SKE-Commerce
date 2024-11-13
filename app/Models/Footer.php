<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Footer extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['tag', 'title', 'link'];

    /**
     * Register media collections for Footer images.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
    }
}
