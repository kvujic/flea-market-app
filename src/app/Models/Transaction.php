<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable =[
        'buyer_id',
        'seller_id',
        'item_id',
        'purchase_id',
        'status',
    ];

    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function chats() {
        return $this->hasMany(Chat::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    public function ratingFrom(int $userId) {
        return $this->ratings()->where('rater_id', $userId)->first();
    }

    public function ratingFor(int $userId) {
        return $this->ratings()->where('rated_id', $userId)->first();
    }
}
