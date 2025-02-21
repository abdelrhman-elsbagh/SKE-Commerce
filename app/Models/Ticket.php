<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'tickets';

    // Define which columns are mass assignable
    protected $fillable = [
        'ticket_category_id',
        'message',
        'status',
        'user_id',
        'response',
    ];

    // Define the relationship to the TicketCategory model
    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function user()
    {
        return $this->belongsTo(User::class); // A ticket belongs to a user.
    }
}
