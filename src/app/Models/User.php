<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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
