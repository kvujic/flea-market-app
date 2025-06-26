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

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // many-to-many relationship (get favorite items through like)
    public function likedItems() {
        return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id');
    }
}
