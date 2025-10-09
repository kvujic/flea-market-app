<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

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

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function purchaseViaTransaction() {
        return $this->hasOneThrough(
            Purchase::class,
            Transaction::class,
            'item_id',
            'id', //purchases
            'id', // items
            'purchase_id'
        );
    }

    public function getEffectivePurchaseAttribute(): ?Purchase {
        $viaTx = $this->relationLoaded('purchaseViaTransaction')
            ? $this->getRelation('purchaseViaTransaction')
            : $this->purchaseViaTransaction()->first();

        if ($viaTx) return $viaTx;

        return $this->relationLoaded('purchase')
            ? $this->getRelation('purchase')
            : $this->purchase()->first();
    }

    public function chats() {
        return $this->hasManyThrough(
            Chat::class,
            Transaction::class,
            'item_id',
            'transaction_id',
            'id', // items
            'id', // transactions
        );
    }

    // one-to-many relationship (child)
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
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

