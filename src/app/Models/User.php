<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // one-to-one relationship
    public function profile() {
        return $this->hasOne(Profile::class);
    }

    // one-to-many relationship
    public function items() {
        return $this->hasMany(Item::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class, 'user_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function transactionsAsBuyer() {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function sales() {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function chats() {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function ratingsGiven() {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function ratingsReceived() {
        return $this->hasMany(Rating::class, 'rated_id');
    }

    // average rating
    protected function averageRatingAttribute(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(
            fn() => ($avg = $this->ratingsReceived()->avg('score')) ? round($avg) : null
        );
    }

    // many-to-many relationship (get favorite items through like)
    public function likedItems() {
        return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id');
    }
}


