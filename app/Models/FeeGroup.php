<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FeeGroup extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'fee_groups';

    protected $fillable = ['fee', 'name'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
