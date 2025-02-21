<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional, as Laravel assumes plural name of the model).
    protected $table = 'ticket_categories';

    // Define which columns are mass assignable
    protected $fillable = [
        'ar_name',
        'name',
        'ar_desc',
        'desc',
    ];

    // Define the relationship to the Ticket model
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
