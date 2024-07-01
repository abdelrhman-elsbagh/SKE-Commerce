<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['business_client_id', 'plan_id', 'start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(BusinessClient::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
