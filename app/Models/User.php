<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'address',
        'date_of_birth',
        'status',
        'fee',
        'fee_group_id',
        'currency_id',
        'phone',
        'secret_key',
        'is_external',
        'domain',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'status' => 'string',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function wallet()
    {
        return $this->hasOne(UserWallet::class);
    }


    public function specialUserFeeDiscounts()
    {
        return $this->hasMany(SpecialUserFeeDiscount::class);
    }

    /**
     * Register the media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')->singleFile();
    }

    public function items()
    {
        return $this->hasMany(Item::class); // Adjust if the foreign key or table name differs
    }


    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('name', $role)->exists();
        }

        if (empty($role) || !is_string($role)) {
            return false;
        }

        return $this->roles()->where('name', $role)->exists();
    }

//    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
//            ->where('model_type', self::class);
//    }


    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_reads')->withPivot('status');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();  // Automatically generate a UUID when creating a user
        });
    }
}
