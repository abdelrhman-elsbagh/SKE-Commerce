<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class PaymentMethod extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'gateway',
        'description',
        'ar_gateway',
        'ar_description'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('payment_method_images')->singleFile();
    }
}
