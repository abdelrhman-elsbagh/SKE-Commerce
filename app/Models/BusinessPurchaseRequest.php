<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BusinessPurchaseRequest extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'business_client_id',
        'amount',
        'status',
        'notes',
    ];

    public function businessClient()
    {
        return $this->belongsTo(BusinessClient::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('business_purchase_documents')->singleFile();
    }
}
