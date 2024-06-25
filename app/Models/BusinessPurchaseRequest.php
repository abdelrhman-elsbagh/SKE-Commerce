<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPurchaseRequest extends Model
{
    use HasFactory;

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

    public function getFirstMediaUrl($collectionName = 'default', $conversionName = '')
    {
        // Assuming you are using a media library like Spatie Media Library, otherwise implement accordingly.
        return $this->getFirstMediaUrl($collectionName, $conversionName);
    }
}
