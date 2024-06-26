<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{

    use HasFactory, Notifiable;

    protected $fillable = [
        'login', 'password', 'first_name', 'last_name', 'gender', 'birthdate',
        'app_name', 'email', 'address', 'role', 'people_living_with', 'has_children',
        'pets', 'average_monthly_income', 'percentage_spent_on_cosmetics', 'vk_profile',
        'telegram_profile', 'profile_photo', 'delivery_address', 'city', 'street',
        'house_number', 'apartment_number', 'entrance', 'postal_code', 'phone', 'verification_code', 'code_sent_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected $appends = ['full_address'];

    public function getFullAddressAttribute()
    {
        $address = "{$this->delivery_address}, {$this->city}, {$this->street}, д. {$this->house_number}";
        if ($this->apartment_number) {
            $address .= ", кв. {$this->apartment_number}";
        }
        if ($this->entrance) {
            $address .= ", подъезд {$this->entrance}";
        }
        $address .= ", {$this->postal_code}";

        return $address;
    }

    public function sampleRequests()
    {
        return $this->hasMany(SampleRequest::class);
    }
    public function testedProducts()
    {
        return $this->hasMany(TestedProduct::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function setProfilePhotoAttribute($value)
    {
        $defaultPhotoUrl = 'https://yobte.ru/uploads/posts/2019-11/krasivye-devushki-v-ochkah-78-foto-27.jpg';
        $this->attributes['profile_photo'] = $value ?: $defaultPhotoUrl;
    }


}
