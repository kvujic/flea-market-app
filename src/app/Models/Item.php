<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'brand',
        'price',
        'condition_id',
        'item_image',
    ];


    // one-to-one relationship (parent)
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }


    // one-to-many relationship (child)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // one-to-many relationship (parent)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // many-to-many relationship
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    // many-to-many relationship (get liked users)
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    // search
    public function scopeSearch($query, $keyword) {
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        return $query;
    }
}

